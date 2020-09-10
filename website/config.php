<?php
 // Connect to the cluster and keyspace "charithrift"
 $cluster  = Cassandra::cluster()
               ->withContactPoints('172.17.0.2') 
               ->build();
 $keyspace  = 'charithrift';
 $session  = $cluster->connect($keyspace);
