<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <pre>
            <?php
            require_once("../../functions.php");

            $t = new CsvParser(dirname(__FILE__) . "/fotobase sogelag.csv");

            $t->chunkSize = 100;
            $t->setDelimiter("semicolon");
            $t->sourceEncoding = "ISO-8859-1";
            $t->parseData();

            echo $t->createTable();


            while ($rows = $t->insertChunk()) {
                echo $rows;
            }

            $t->dropTable();
            ?>

        </pre>        

    </body>
</html>
