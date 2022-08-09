#!/bin/bash
docker run --name kib01-test --net php-beauty_pimcore_network -p 127.0.0.1:5601:5601 -e "ELASTICSEARCH_HOSTS=http://elasticsearch:9200" docker.elastic.co/kibana/kibana:7.17.5
