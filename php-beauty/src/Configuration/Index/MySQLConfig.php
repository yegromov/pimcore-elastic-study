<?php
namespace App\Configuration\Index;

use Pimcore\Bundle\EcommerceFrameworkBundle\IndexService\Config\DefaultMysql;

class MySQLConfig extends DefaultMysql
{

    public function getTablename()
    {
        return '__academy_index';
    }

    public function getRelationTablename()
    {
        return '__academy_index_relations';
    }
}

