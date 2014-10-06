<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'domain' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.LF14.SysMgmtBundle.Model.map
 */
class DomainTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.DomainTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('domain');
        $this->setPhpName('Domain');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\Domain');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 45, null);
        $this->addColumn('notes', 'Notes', 'LONGVARCHAR', false, null, null);
        $this->addColumn('date_start', 'DateStart', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('date_end', 'DateEnd', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('client_id', 'ClientId', 'INTEGER', 'client', 'id', false, null, null);
        $this->addForeignKey('nameserver_id', 'NameserverId', 'INTEGER', 'nameserver', 'id', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Client', 'LF14\\SysMgmtBundle\\Model\\Client', RelationMap::MANY_TO_ONE, array('client_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('Nameserver', 'LF14\\SysMgmtBundle\\Model\\Nameserver', RelationMap::MANY_TO_ONE, array('nameserver_id' => 'id', ), 'SET NULL', null);
        $this->addRelation('Hosting', 'LF14\\SysMgmtBundle\\Model\\Hosting', RelationMap::ONE_TO_MANY, array('id' => 'domain_id', ), 'SET NULL', null, 'Hostings');
        $this->addRelation('Mailbox', 'LF14\\SysMgmtBundle\\Model\\Mailbox', RelationMap::ONE_TO_MANY, array('id' => 'domain_id', ), 'SET NULL', null, 'Mailboxen');
    } // buildRelations()

} // DomainTableMap
