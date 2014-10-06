<?php

namespace LF14\SysMgmtBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use LF14\SysMgmtBundle\Model\ClientPeer;
use LF14\SysMgmtBundle\Model\Host;
use LF14\SysMgmtBundle\Model\HostIpPeer;
use LF14\SysMgmtBundle\Model\HostPeer;
use LF14\SysMgmtBundle\Model\HostStatusPeer;
use LF14\SysMgmtBundle\Model\HostTypePeer;
use LF14\SysMgmtBundle\Model\HostingPeer;
use LF14\SysMgmtBundle\Model\LocationPeer;
use LF14\SysMgmtBundle\Model\LoginPeer;
use LF14\SysMgmtBundle\Model\OsPeer;
use LF14\SysMgmtBundle\Model\map\HostTableMap;

abstract class BaseHostPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'host';

    /** the related Propel class for this table */
    const OM_CLASS = 'LF14\\SysMgmtBundle\\Model\\Host';

    /** the related TableMap class for this table */
    const TM_CLASS = 'LF14\\SysMgmtBundle\\Model\\map\\HostTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 11;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 11;

    /** the column name for the id field */
    const ID = 'host.id';

    /** the column name for the name field */
    const NAME = 'host.name';

    /** the column name for the notes field */
    const NOTES = 'host.notes';

    /** the column name for the client_id field */
    const CLIENT_ID = 'host.client_id';

    /** the column name for the location_id field */
    const LOCATION_ID = 'host.location_id';

    /** the column name for the host_type_id field */
    const HOST_TYPE_ID = 'host.host_type_id';

    /** the column name for the host_status_id field */
    const HOST_STATUS_ID = 'host.host_status_id';

    /** the column name for the os_id field */
    const OS_ID = 'host.os_id';

    /** the column name for the parent_id field */
    const PARENT_ID = 'host.parent_id';

    /** the column name for the created_at field */
    const CREATED_AT = 'host.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'host.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Host objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Host[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. HostPeer::$fieldNames[HostPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Name', 'Notes', 'ClientId', 'LocationId', 'HostTypeId', 'HostStatusId', 'OsId', 'ParentId', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'name', 'notes', 'clientId', 'locationId', 'hostTypeId', 'hostStatusId', 'osId', 'parentId', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (HostPeer::ID, HostPeer::NAME, HostPeer::NOTES, HostPeer::CLIENT_ID, HostPeer::LOCATION_ID, HostPeer::HOST_TYPE_ID, HostPeer::HOST_STATUS_ID, HostPeer::OS_ID, HostPeer::PARENT_ID, HostPeer::CREATED_AT, HostPeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'NAME', 'NOTES', 'CLIENT_ID', 'LOCATION_ID', 'HOST_TYPE_ID', 'HOST_STATUS_ID', 'OS_ID', 'PARENT_ID', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'name', 'notes', 'client_id', 'location_id', 'host_type_id', 'host_status_id', 'os_id', 'parent_id', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. HostPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Name' => 1, 'Notes' => 2, 'ClientId' => 3, 'LocationId' => 4, 'HostTypeId' => 5, 'HostStatusId' => 6, 'OsId' => 7, 'ParentId' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'name' => 1, 'notes' => 2, 'clientId' => 3, 'locationId' => 4, 'hostTypeId' => 5, 'hostStatusId' => 6, 'osId' => 7, 'parentId' => 8, 'createdAt' => 9, 'updatedAt' => 10, ),
        BasePeer::TYPE_COLNAME => array (HostPeer::ID => 0, HostPeer::NAME => 1, HostPeer::NOTES => 2, HostPeer::CLIENT_ID => 3, HostPeer::LOCATION_ID => 4, HostPeer::HOST_TYPE_ID => 5, HostPeer::HOST_STATUS_ID => 6, HostPeer::OS_ID => 7, HostPeer::PARENT_ID => 8, HostPeer::CREATED_AT => 9, HostPeer::UPDATED_AT => 10, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'NAME' => 1, 'NOTES' => 2, 'CLIENT_ID' => 3, 'LOCATION_ID' => 4, 'HOST_TYPE_ID' => 5, 'HOST_STATUS_ID' => 6, 'OS_ID' => 7, 'PARENT_ID' => 8, 'CREATED_AT' => 9, 'UPDATED_AT' => 10, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'name' => 1, 'notes' => 2, 'client_id' => 3, 'location_id' => 4, 'host_type_id' => 5, 'host_status_id' => 6, 'os_id' => 7, 'parent_id' => 8, 'created_at' => 9, 'updated_at' => 10, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = HostPeer::getFieldNames($toType);
        $key = isset(HostPeer::$fieldKeys[$fromType][$name]) ? HostPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(HostPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, HostPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return HostPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. HostPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(HostPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(HostPeer::ID);
            $criteria->addSelectColumn(HostPeer::NAME);
            $criteria->addSelectColumn(HostPeer::NOTES);
            $criteria->addSelectColumn(HostPeer::CLIENT_ID);
            $criteria->addSelectColumn(HostPeer::LOCATION_ID);
            $criteria->addSelectColumn(HostPeer::HOST_TYPE_ID);
            $criteria->addSelectColumn(HostPeer::HOST_STATUS_ID);
            $criteria->addSelectColumn(HostPeer::OS_ID);
            $criteria->addSelectColumn(HostPeer::PARENT_ID);
            $criteria->addSelectColumn(HostPeer::CREATED_AT);
            $criteria->addSelectColumn(HostPeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.notes');
            $criteria->addSelectColumn($alias . '.client_id');
            $criteria->addSelectColumn($alias . '.location_id');
            $criteria->addSelectColumn($alias . '.host_type_id');
            $criteria->addSelectColumn($alias . '.host_status_id');
            $criteria->addSelectColumn($alias . '.os_id');
            $criteria->addSelectColumn($alias . '.parent_id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(HostPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return Host
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = HostPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return HostPeer::populateObjects(HostPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            HostPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param Host $obj A Host object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            HostPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A Host object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Host) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Host object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(HostPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Host Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(HostPeer::$instances[$key])) {
                return HostPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (HostPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        HostPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to host
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in HostPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        HostPeer::clearInstancePool();
        // Invalidate objects in HostIpPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        HostIpPeer::clearInstancePool();
        // Invalidate objects in HostingPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        HostingPeer::clearInstancePool();
        // Invalidate objects in LoginPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        LoginPeer::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = HostPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = HostPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                HostPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (Host object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = HostPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = HostPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + HostPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = HostPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            HostPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Client table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinClient(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related HostStatus table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinHostStatus(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related HostType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinHostType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Location table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinLocation(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Os table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinOs(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Host objects pre-filled with their Client objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinClient(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol = HostPeer::NUM_HYDRATE_COLUMNS;
        ClientPeer::addSelectColumns($criteria);

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ClientPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Host) to $obj2 (Client)
                $obj2->addHost($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with their HostStatus objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinHostStatus(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol = HostPeer::NUM_HYDRATE_COLUMNS;
        HostStatusPeer::addSelectColumns($criteria);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = HostStatusPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = HostStatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    HostStatusPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Host) to $obj2 (HostStatus)
                $obj2->addHost($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with their HostType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinHostType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol = HostPeer::NUM_HYDRATE_COLUMNS;
        HostTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = HostTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = HostTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    HostTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Host) to $obj2 (HostType)
                $obj2->addHost($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with their Location objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinLocation(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol = HostPeer::NUM_HYDRATE_COLUMNS;
        LocationPeer::addSelectColumns($criteria);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = LocationPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = LocationPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    LocationPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Host) to $obj2 (Location)
                $obj2->addHost($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with their Os objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinOs(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol = HostPeer::NUM_HYDRATE_COLUMNS;
        OsPeer::addSelectColumns($criteria);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = OsPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = OsPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    OsPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Host) to $obj2 (Os)
                $obj2->addHost($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of Host objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        ClientPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ClientPeer::NUM_HYDRATE_COLUMNS;

        HostStatusPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostStatusPeer::NUM_HYDRATE_COLUMNS;

        HostTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + HostTypePeer::NUM_HYDRATE_COLUMNS;

        LocationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + LocationPeer::NUM_HYDRATE_COLUMNS;

        OsPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + OsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Client rows

            $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = ClientPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Host) to the collection in $obj2 (Client)
                $obj2->addHost($obj1);
            } // if joined row not null

            // Add objects for joined HostStatus rows

            $key3 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = HostStatusPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = HostStatusPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostStatusPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostStatus)
                $obj3->addHost($obj1);
            } // if joined row not null

            // Add objects for joined HostType rows

            $key4 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = HostTypePeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = HostTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    HostTypePeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Host) to the collection in $obj4 (HostType)
                $obj4->addHost($obj1);
            } // if joined row not null

            // Add objects for joined Location rows

            $key5 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = LocationPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = LocationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    LocationPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Location)
                $obj5->addHost($obj1);
            } // if joined row not null

            // Add objects for joined Os rows

            $key6 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = OsPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = OsPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    OsPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Host) to the collection in $obj6 (Os)
                $obj6->addHost($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Client table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptClient(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related HostRelatedByParentId table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptHostRelatedByParentId(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related HostStatus table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptHostStatus(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related HostType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptHostType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Location table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptLocation(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Os table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptOs(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(HostPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            HostPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Host objects pre-filled with all related objects except Client.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptClient(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        HostStatusPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + HostStatusPeer::NUM_HYDRATE_COLUMNS;

        HostTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostTypePeer::NUM_HYDRATE_COLUMNS;

        LocationPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + LocationPeer::NUM_HYDRATE_COLUMNS;

        OsPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + OsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined HostStatus rows

                $key2 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = HostStatusPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = HostStatusPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    HostStatusPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Host) to the collection in $obj2 (HostStatus)
                $obj2->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostType rows

                $key3 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = HostTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = HostTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostType)
                $obj3->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Location rows

                $key4 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = LocationPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = LocationPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    LocationPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Host) to the collection in $obj4 (Location)
                $obj4->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Os rows

                $key5 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = OsPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = OsPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    OsPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Os)
                $obj5->addHost($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with all related objects except HostRelatedByParentId.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptHostRelatedByParentId(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        ClientPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ClientPeer::NUM_HYDRATE_COLUMNS;

        HostStatusPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostStatusPeer::NUM_HYDRATE_COLUMNS;

        HostTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + HostTypePeer::NUM_HYDRATE_COLUMNS;

        LocationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + LocationPeer::NUM_HYDRATE_COLUMNS;

        OsPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + OsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Client rows

                $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ClientPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Host) to the collection in $obj2 (Client)
                $obj2->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostStatus rows

                $key3 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = HostStatusPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = HostStatusPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostStatusPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostStatus)
                $obj3->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostType rows

                $key4 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = HostTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = HostTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    HostTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Host) to the collection in $obj4 (HostType)
                $obj4->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Location rows

                $key5 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = LocationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = LocationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    LocationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Location)
                $obj5->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Os rows

                $key6 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = OsPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = OsPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    OsPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (Host) to the collection in $obj6 (Os)
                $obj6->addHost($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with all related objects except HostStatus.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptHostStatus(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        ClientPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ClientPeer::NUM_HYDRATE_COLUMNS;

        HostTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostTypePeer::NUM_HYDRATE_COLUMNS;

        LocationPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + LocationPeer::NUM_HYDRATE_COLUMNS;

        OsPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + OsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Client rows

                $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ClientPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Host) to the collection in $obj2 (Client)
                $obj2->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostType rows

                $key3 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = HostTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = HostTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostType)
                $obj3->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Location rows

                $key4 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = LocationPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = LocationPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    LocationPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Host) to the collection in $obj4 (Location)
                $obj4->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Os rows

                $key5 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = OsPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = OsPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    OsPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Os)
                $obj5->addHost($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with all related objects except HostType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptHostType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        ClientPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ClientPeer::NUM_HYDRATE_COLUMNS;

        HostStatusPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostStatusPeer::NUM_HYDRATE_COLUMNS;

        LocationPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + LocationPeer::NUM_HYDRATE_COLUMNS;

        OsPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + OsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Client rows

                $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ClientPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Host) to the collection in $obj2 (Client)
                $obj2->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostStatus rows

                $key3 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = HostStatusPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = HostStatusPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostStatusPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostStatus)
                $obj3->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Location rows

                $key4 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = LocationPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = LocationPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    LocationPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Host) to the collection in $obj4 (Location)
                $obj4->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Os rows

                $key5 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = OsPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = OsPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    OsPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Os)
                $obj5->addHost($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with all related objects except Location.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptLocation(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        ClientPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ClientPeer::NUM_HYDRATE_COLUMNS;

        HostStatusPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostStatusPeer::NUM_HYDRATE_COLUMNS;

        HostTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + HostTypePeer::NUM_HYDRATE_COLUMNS;

        OsPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + OsPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::OS_ID, OsPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Client rows

                $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ClientPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Host) to the collection in $obj2 (Client)
                $obj2->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostStatus rows

                $key3 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = HostStatusPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = HostStatusPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostStatusPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostStatus)
                $obj3->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostType rows

                $key4 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = HostTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = HostTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    HostTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Host) to the collection in $obj4 (HostType)
                $obj4->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Os rows

                $key5 = OsPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = OsPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = OsPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    OsPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Os)
                $obj5->addHost($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Host objects pre-filled with all related objects except Os.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Host objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptOs(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(HostPeer::DATABASE_NAME);
        }

        HostPeer::addSelectColumns($criteria);
        $startcol2 = HostPeer::NUM_HYDRATE_COLUMNS;

        ClientPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ClientPeer::NUM_HYDRATE_COLUMNS;

        HostStatusPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + HostStatusPeer::NUM_HYDRATE_COLUMNS;

        HostTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + HostTypePeer::NUM_HYDRATE_COLUMNS;

        LocationPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + LocationPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(HostPeer::CLIENT_ID, ClientPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_STATUS_ID, HostStatusPeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::HOST_TYPE_ID, HostTypePeer::ID, $join_behavior);

        $criteria->addJoin(HostPeer::LOCATION_ID, LocationPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = HostPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = HostPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = HostPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                HostPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Client rows

                $key2 = ClientPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = ClientPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = ClientPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ClientPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Host) to the collection in $obj2 (Client)
                $obj2->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostStatus rows

                $key3 = HostStatusPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = HostStatusPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = HostStatusPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    HostStatusPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Host) to the collection in $obj3 (HostStatus)
                $obj3->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined HostType rows

                $key4 = HostTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = HostTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = HostTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    HostTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Host) to the collection in $obj4 (HostType)
                $obj4->addHost($obj1);

            } // if joined row is not null

                // Add objects for joined Location rows

                $key5 = LocationPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = LocationPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = LocationPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    LocationPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Host) to the collection in $obj5 (Location)
                $obj5->addHost($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(HostPeer::DATABASE_NAME)->getTable(HostPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseHostPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseHostPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \LF14\SysMgmtBundle\Model\map\HostTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return HostPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Host or Criteria object.
     *
     * @param      mixed $values Criteria or Host object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Host object
        }

        if ($criteria->containsKey(HostPeer::ID) && $criteria->keyContainsValue(HostPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.HostPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a Host or Criteria object.
     *
     * @param      mixed $values Criteria or Host object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(HostPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(HostPeer::ID);
            $value = $criteria->remove(HostPeer::ID);
            if ($value) {
                $selectCriteria->add(HostPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(HostPeer::TABLE_NAME);
            }

        } else { // $values is Host object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the host table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += HostPeer::doOnDeleteCascade(new Criteria(HostPeer::DATABASE_NAME), $con);
            HostPeer::doOnDeleteSetNull(new Criteria(HostPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(HostPeer::TABLE_NAME, $con, HostPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            HostPeer::clearInstancePool();
            HostPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Host or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Host object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Host) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(HostPeer::DATABASE_NAME);
            $criteria->add(HostPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(HostPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += HostPeer::doOnDeleteCascade($c, $con);

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            HostPeer::doOnDeleteSetNull($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                HostPeer::clearInstancePool();
            } elseif ($values instanceof Host) { // it's a model object
                HostPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    HostPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            HostPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
     * feature (like MySQL or SQLite).
     *
     * This method is not very speedy because it must perform a query first to get
     * the implicated records and then perform the deletes by calling those Peer classes.
     *
     * This method should be used within a transaction if possible.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
    {
        // initialize var to track total num of affected rows
        $affectedRows = 0;

        // first find the objects that are implicated by the $criteria
        $objects = HostPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related HostIp objects
            $criteria = new Criteria(HostIpPeer::DATABASE_NAME);

            $criteria->add(HostIpPeer::HOST_ID, $obj->getId());
            $affectedRows += HostIpPeer::doDelete($criteria, $con);

            // delete related Login objects
            $criteria = new Criteria(LoginPeer::DATABASE_NAME);

            $criteria->add(LoginPeer::HOST_ID, $obj->getId());
            $affectedRows += LoginPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * This is a method for emulating ON DELETE SET NULL DBs that don't support this
     * feature (like MySQL or SQLite).
     *
     * This method is not very speedy because it must perform a query first to get
     * the implicated records and then perform the deletes by calling those Peer classes.
     *
     * This method should be used within a transaction if possible.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return void
     */
    protected static function doOnDeleteSetNull(Criteria $criteria, PropelPDO $con)
    {

        // first find the objects that are implicated by the $criteria
        $objects = HostPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {

            // set fkey col in related Host rows to null
            $selectCriteria = new Criteria(HostPeer::DATABASE_NAME);
            $updateValues = new Criteria(HostPeer::DATABASE_NAME);
            $selectCriteria->add(HostPeer::PARENT_ID, $obj->getId());
            $updateValues->add(HostPeer::PARENT_ID, null);

            BasePeer::doUpdate($selectCriteria, $updateValues, $con); // use BasePeer because generated Peer doUpdate() methods only update using pkey

            // set fkey col in related Hosting rows to null
            $selectCriteria = new Criteria(HostPeer::DATABASE_NAME);
            $updateValues = new Criteria(HostPeer::DATABASE_NAME);
            $selectCriteria->add(HostingPeer::HOST_ID, $obj->getId());
            $updateValues->add(HostingPeer::HOST_ID, null);

            BasePeer::doUpdate($selectCriteria, $updateValues, $con); // use BasePeer because generated Peer doUpdate() methods only update using pkey

        }
    }

    /**
     * Validates all modified columns of given Host object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Host $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(HostPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(HostPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(HostPeer::DATABASE_NAME, HostPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Host
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = HostPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(HostPeer::DATABASE_NAME);
        $criteria->add(HostPeer::ID, $pk);

        $v = HostPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Host[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(HostPeer::DATABASE_NAME);
            $criteria->add(HostPeer::ID, $pks, Criteria::IN);
            $objs = HostPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseHostPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseHostPeer::buildTableMap();

