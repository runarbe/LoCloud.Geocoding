<?php

require_once("./lib/class.KML.php");
require_once("./lib/class.GeoJSON.php");
require_once("functions.php");

$mTstamp = date("Ymd-hi");


if ((isset($_GET["ds"])) && ($_GET["ds"] != "")) {
    $mDs = $_GET["ds"];

    $mDb = db();

    /*
     * Get the 
     */
    $mGetDatasetSql = sprintf("SELECT * FROM meta_datasources WHERE ds_table='%s'", $mDs);
    $mResDataset = $mDb->query($mGetDatasetSql);
    if ($mDb->affected_rows == 1) {
        $mDs = $mResDataset->fetch_object();
    }
    
    if (!$mDs->ds_col_cat === "null") {
        $mDs->ds_col_cat =  "d.".$mDs->ds_col_cat ;
    }

    $mSql = sprintf("SELECT DISTINCT
                        dm.gc_lon as gc_lon,
                        dm.gc_lat as gc_lat,
                        trim(dm.gc_name) as gc_name,
                        dm.gc_probability as gc_probability,
                        %s as gc_cat,
                        d.*
                    FROM
                        %s d
                    LEFT JOIN
                        %s_match dm
                    ON
                        dm.fk_ds_id = d.%s AND dm.id = (SELECT id FROM %s_match WHERE fk_ds_id = dm.fk_ds_id ORDER BY gc_probability LIMIT 1)
                    WHERE
                        (dm.gc_lon IS NOT NULL AND dm.gc_lat IS NOT NULL)
                    AND 
                        (dm.gc_lon <> 0 OR dm.gc_lat <> 0)", $mDs->ds_col_cat, $mDs->ds_table, $mDs->ds_table, $mDs->ds_col_pk, $mDs->ds_table
    );
    
    $mRes = $mDb->query($mSql);
    if ($mRes) {

        /*
         * Choose formats
         */
        $mFormat = "kml";
        if ((isset($_GET["format"])) && ($_GET["format"] != "")) {
            $mFormat = $_GET["format"];
        }

        if ($mFormat == "kml") {

            $mKml = new KML();
            $mKml->addStyle("style-0", "./images/hotel0.png");
            $mKml->addStyle("style-1", "./images/hotel1.png");
            $mKml->addStyle("style-2", "./images/hotel2.png");
            $mKml->addStyle("style-3", "./images/hotel3.png");
            $mKml->addStyle("style-4", "./images/hotel4.png");
            $mKml->addStyle("style-5", "./images/hotel5.png");

            while ($obj = $mRes->fetch_object()) {
                $mKml->addPlacemark($obj->gc_name, $obj->gc_lon, $obj->gc_lat, "", "style-" . $obj->category);
            }
            if ($_GET["output"] != "true") {
                header("Content-Disposition: attachment; filename='" . $mTstamp . "-" . $mDs->ds_title . ".kml'");
            }
            echo $mKml->getKml();
        } elseif ($mFormat == "geojson.js" || $mFormat == "geojson") {
            $mGeoJSON = new GeoJSON();
            while ($obj = $mRes->fetch_object()) {
                $mGeometry = new geometry("Point", array(round($obj->gc_lon, 6), round($obj->gc_lat, 6)));
                $mArray = (array) $obj;
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
                $mGeoJSON->createFeature($mGeometry, $mProperties);
            }
            
            // Send content header
            if ($_GET["output"] != "true") {
                header("Content-Disposition: attachment; filename='" . $mTstamp . "-" . $mDs->ds_title . ".".$mFormat."'");
            }
            
            // Output json data
            if ($mFormat == "geojson") {
                echo $mGeoJSON->getGeoJSON();
            } else {
                echo "var GeoJSONData =" . $mGeoJSON->getGeoJSON() . ";";
            }
        } elseif ($mFormat == "pgsql") {
            
        } elseif ($mFormat == "mysql") {
            
        } elseif ($mFormat == "oracle") {
            
        }
    }
    dbc($mDb);
}
?>
