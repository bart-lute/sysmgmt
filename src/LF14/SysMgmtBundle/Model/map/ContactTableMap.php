<?php

namespace LF14\SysMgmtBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'contact' table.
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
class ContactTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LF14.SysMgmtBundle.Model.map.ContactTableMap';

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
        $this->setName('contact');
        $this->setPhpName('Contact');
        $this->setClassname('LF14\\SysMgmtBundle\\Model\\Contact');
        $this->setPackage('src.LF14.SysMgmtBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 128, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 128, null);
        $this->addColumn('address', 'Address', 'LONGVARCHAR', false, null, null);
        $this->addColumn('phone1', 'Phone1', 'VARCHAR', false, 45, null);
        $this->addColumn('phone2', 'Phone2', 'VARCHAR', false, 45, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, 'CURRENT_TIMESTAMP');
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ClientContact', 'LF14\\SysMgmtBundle\\Model\\ClientContact', RelationMap::ONE_TO_MANY, array('id' => 'contact_id', ), 'CASCADE', null, 'ClientContacts');
        $this->addRelation('Location', 'LF14\\SysMgmtBundle\\Model\\Location', RelationMap::ONE_TO_MANY, array('id' => 'contact_id', ), 'SET NULL', null, 'Locations');
    } // buildRelations()

} // ContactTableMap
