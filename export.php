<?php

require_once("./lib/class.KML.php");
require_once("./lib/class.GeoJSON.php");
require_once("./lib/class.RDF.php");
require_once("functions.php");

$mTstamp = date("Ymd-hi");

$mDs = filter_input(INPUT_GET,
        "ds",
        FILTER_DEFAULT);

if ($mDs !== false && $mDs !== null) {

    $mDb = db();

    /*
     * Get the data
     */

    $mGetDatasetSql = sprintf("SELECT * FROM meta_datasources WHERE ds_table='%s'",
            $mDs);
    $mResDataset = $mDb->query($mGetDatasetSql);

    if ($mDb->affected_rows == 1) {
        $mDs = $mResDataset->fetch_object();
    } else {
        die("Datasource #$mDs does not exist.");
    }


    if (!$mDs->ds_col_cat === "null") {
        $mDs->ds_col_cat = "d." . $mDs->ds_col_cat;
    }

    if (!$mDs->ds_col_name === "null") {
        $mDs->ds_col_name = "d." . $mDs->ds_col_name;
    }

    if (!$mDs->ds_col_puri === "null") {
        $mDs->ds_col_puri = "d." . $mDs->ds_col_puri;
    }

    $mSql = sprintf("
        SELECT DISTINCT
            dm.gc_lon as gc_lon,
            dm.gc_lat as gc_lat,
            trim(dm.gc_name) as gc_name,
            dm.gc_probability as gc_probability,
            dm.gc_dbsearch_puri as gc_dbsearch_puri,
            %s as gc_orig_name,
            %s as gc_puri,
            %s as gc_cat,
            d.*
        FROM
            %s d
        LEFT JOIN
            %s_match dm
        ON (
            dm.fk_ds_id = d.%s AND
            dm.id = (
                SELECT
                    id
                FROM
                    %s_match
                WHERE
                    fk_ds_id = dm.fk_ds_id
                ORDER BY
                    gc_probability
                LIMIT 1
                )
            )
        WHERE
            (
            dm.gc_lon IS NOT NULL AND
            dm.gc_lat IS NOT NULL
            )
        AND 
            (
            dm.gc_lon <> 0 OR
            dm.gc_lat <> 0
            )",
            nullIfEmpty($mDs->ds_col_name),
            nullIfEmpty($mDs->ds_col_puri),
            nullIfEmpty($mDs->ds_col_cat),
            $mDs->ds_table,
            $mDs->ds_table,
            $mDs->ds_col_pk,
            $mDs->ds_table
    );

    $mRes = Db::query($mSql);
    if (!$mRes) {
        die("Error while selecting data from data source $mDs->ds_table");
    } else {
        if (mysqli_num_rows($mRes) === 0) {
            die("No geocoded entries in data source $mDs->ds_table");
        }

        /*
         * Get input format from HTTP request and choose process accordingly
         */
        $mFormat = filter_input(INPUT_GET,
                "format",
                FILTER_DEFAULT);

        if (!$mFormat) {
            $mFormat = "kml";
        }

        switch ($mFormat) {

            case "kml":

                $mKml = new KML();
                $mKml->addStyle("style-0",
                        "./images/hotel0.png");
                $mKml->addStyle("style-1",
                        "./images/hotel1.png");
                $mKml->addStyle("style-2",
                        "./images/hotel2.png");
                $mKml->addStyle("style-3",
                        "./images/hotel3.png");
                $mKml->addStyle("style-4",
                        "./images/hotel4.png");
                $mKml->addStyle("style-5",
                        "./images/hotel5.png");

                while ($mGMatch = $mRes->fetch_object()) {
                    $mKml->addPlacemark($mGMatch->gc_name,
                            $mGMatch->gc_lon,
                            $mGMatch->gc_lat,
                            "",
                            "style-" . $mGMatch->category);
                }
                if ($_GET["output"] != "true") {
                    header("Content-Disposition: attachment; filename='" . $mTstamp . "-" . $mDs->ds_title . ".kml'");
                }
                echo $mKml->getKml();
                break;
            case "geojson.js";
            case "geojson":
                $mGeoJSON = new GeoJSON();
                while ($mGMatch = $mRes->fetch_object()) {
                    $mGeometry = new geometry("Point",
                            array(round($mGMatch->gc_lon,
                                6), round($mGMatch->gc_lat,
                                6)));
                    $mArray = (array) $mGMatch;
                    unset($mArray["fk_ds_id"]);
                    unset($mArray["gc_lon"]);
                    unset($mArray["gc_lat"]);
                    unset($mArray["gc_fieldchanges"]);
                    unset($mArray["gc_geom"]);
                    unset($mArray["gc_usr_id"]);
                    unset($mArray["gc_timestamp"]);
//unset($mArray["gc_cat"]);
//unset($mArray["gc_probability"]);
                    unset($mArray[$mDs->ds_col_cat]);
                    unset($mArray[$mDs->ds_col_x]);
                    unset($mArray[$mDs->ds_col_y]);

                    $mProperties = new properties($mArray);
                    $mGeoJSON->createFeature($mGeometry,
                            $mProperties);
                }

// Send content header
                if ($_GET["output"] != "true") {
                    header("Content-Disposition: attachment; filename='" . $mTstamp . "-" . $mDs->ds_title . "." . $mFormat . "'");
                }

// Output json data
                if ($mFormat == "geojson") {
                    echo $mGeoJSON->getGeoJSON();
                } else {
                    echo "var GeoJSONData =" . $mGeoJSON->getGeoJSON() . ";";
                }
                break;
            case "rdf":
                $mDoc = new RDF();
                $mNumRecs = 0;
                while ($mGMatch = $mRes->fetch_object()) {
                    /* @var $mGMatch GeocodingMatch */
                    if ($mGMatch->gc_puri !== null) {
                        $mDoc->addDesc($mGMatch->gc_puri,
                                $mGMatch->gc_lat,
                                $mGMatch->gc_lon,
                                $mGMatch->gc_probability,
                                $mGMatch->gc_orig_name,
                                $mGMatch->gc_name,
                                $mGMatch->gc_dbsearch_puri);
                        $mNumRecs++;
                    }
                }
                
                if ($mNumRecs === 0) {
                    die("Error, no records in $mDs->ds_table have persistent URIs.");
                }
                
                header("Content-Type: application/rdf+xml");
                header("Content-Disposition: attachment; filename='" . $mTstamp . "-" . $mDs->ds_title . ".rdf'");
                echo $mDoc->getXml();
                
                break;
            case "csv":
                $mFileName = $mTstamp . "-" . $mDs->ds_title . ".csv";
                $mCtr = 0;
                header("Content-Type: text/csv; name='$mFileName'");
                header("Content-Disposition: attachment; filename='$mFileName'");
                while ($mArray = mysqli_fetch_assoc($mRes)) {
                    if ($mCtr === 0) {
                        echo "\"" . implode("\"; \"",
                                array_map(addslashes,
                                        array_keys($mArray))) . "\"\r\n";
                    }
                    echo "\"" . implode("\"; \"",
                            array_map(addslashes,
                                    array_values($mArray))) . "\"\r\n";

                    $mCtr++;
                }
                break;
            default:
                die("Format $mFormat not supported");
                break;
        }
    }
    dbc($mDb);
}