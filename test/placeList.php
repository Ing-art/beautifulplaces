<?php

echo "<h1>Test place list</h1>";

$place = Place::findOrFail(9)->iduser;

dd(!is_null($place)); //ok
