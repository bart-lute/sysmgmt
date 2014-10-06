<?php

namespace LF14\SysMgmtBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LF14\SysMgmtBundle\Model\Client;
use LF14\SysMgmtBundle\Model\ClientContact;
use LF14\SysMgmtBundle\Model\ClientContactQuery;
use LF14\SysMgmtBundle\Model\ClientPeer;
use LF14\SysMgmtBundle\Model\ClientQuery;
use LF14\SysMgmtBundle\Model\Domain;
use LF14\SysMgmtBundle\Model\DomainQuery;
use LF14\SysMgmtBundle\Model\Host;
use LF14\SysMgmtBundle\Model\HostQuery;

abstract class BaseClient extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'LF14\\SysMgmtBundle\\Model\\ClientPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ClientPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the notes field.
     * @var        string
     */
    protected $notes;

    /**
     * The value for the created_at field.
     * Note: this column has a database default value of: (expression) CURRENT_TIMESTAMP
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        PropelObjectCollection|ClientContact[] Collection to store aggregation of ClientContact objects.
     */
    protected $collClientContacts;
    protected $collClientContactsPartial;

    /**
     * @var        PropelObjectCollection|Domain[] Collection to store aggregation of Domain objects.
     */
    protected $collDomains;
    protected $collDomainsPartial;

    /**
     * @var        PropelObjectCollection|Host[] Collection to store aggregation of Host objects.
     */
    protected $collHosts;
    protected $collHostsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $clientContactsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $domainsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $hostsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
    }

    /**
     * Initializes internal state of BaseClient object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [notes] column value.
     *
     * @return string
     */
    public function getNotes()
    {

        return $this->notes;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Client The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ClientPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Client The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = ClientPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [notes] column.
     *
     * @param  string $v new value
     * @return Client The current object (for fluent API support)
     */
    public function setNotes($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->notes !== $v) {
            $this->notes = $v;
            $this->modifiedColumns[] = ClientPeer::NOTES;
        }


        return $this;
    } // setNotes()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Client The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = ClientPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Client The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = ClientPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->notes = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->created_at = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->updated_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 5; // 5 = ClientPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Client object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ClientPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ClientPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collClientContacts = null;

            $this->collDomains = null;

            $this->collHosts = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ClientPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ClientQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(ClientPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ClientPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->clientContactsScheduledForDeletion !== null) {
                if (!$this->clientContactsScheduledForDeletion->isEmpty()) {
                    ClientContactQuery::create()
                        ->filterByPrimaryKeys($this->clientContactsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->clientContactsScheduledForDeletion = null;
                }
            }

            if ($this->collClientContacts !== null) {
                foreach ($this->collClientContacts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->domainsScheduledForDeletion !== null) {
                if (!$this->domainsScheduledForDeletion->isEmpty()) {
                    foreach ($this->domainsScheduledForDeletion as $domain) {
                        // need to save related object because we set the relation to null
                        $domain->save($con);
                    }
                    $this->domainsScheduledForDeletion = null;
                }
            }

            if ($this->collDomains !== null) {
                foreach ($this->collDomains as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->hostsScheduledForDeletion !== null) {
                if (!$this->hostsScheduledForDeletion->isEmpty()) {
                    foreach ($this->hostsScheduledForDeletion as $host) {
                        // need to save related object because we set the relation to null
                        $host->save($con);
                    }
                    $this->hostsScheduledForDeletion = null;
                }
            }

            if ($this->collHosts !== null) {
                foreach ($this->collHosts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = ClientPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ClientPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ClientPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ClientPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(ClientPeer::NOTES)) {
            $modifiedColumns[':p' . $index++]  = '`notes`';
        }
        if ($this->isColumnModified(ClientPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(ClientPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `client` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`notes`':
                        $stmt->bindValue($identifier, $this->notes, PDO::PARAM_STR);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = ClientPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collClientContacts !== null) {
                    foreach ($this->collClientContacts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collDomains !== null) {
                    foreach ($this->collDomains as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collHosts !== null) {
                    foreach ($this->collHosts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = ClientPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getNotes();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Client'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Client'][$this->getPrimaryKey()] = true;
        $keys = ClientPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getNotes(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collClientContacts) {
                $result['ClientContacts'] = $this->collClientContacts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDomains) {
                $result['Domains'] = $this->collDomains->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collHosts) {
                $result['Hosts'] = $this->collHosts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = ClientPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setNotes($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = ClientPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setNotes($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCreatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ClientPeer::DATABASE_NAME);

        if ($this->isColumnModified(ClientPeer::ID)) $criteria->add(ClientPeer::ID, $this->id);
        if ($this->isColumnModified(ClientPeer::NAME)) $criteria->add(ClientPeer::NAME, $this->name);
        if ($this->isColumnModified(ClientPeer::NOTES)) $criteria->add(ClientPeer::NOTES, $this->notes);
        if ($this->isColumnModified(ClientPeer::CREATED_AT)) $criteria->add(ClientPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(ClientPeer::UPDATED_AT)) $criteria->add(ClientPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(ClientPeer::DATABASE_NAME);
        $criteria->add(ClientPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Client (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setNotes($this->getNotes());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getClientContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addClientContact($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDomains() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDomain($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getHosts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addHost($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Client Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return ClientPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ClientPeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ClientContact' == $relationName) {
            $this->initClientContacts();
        }
        if ('Domain' == $relationName) {
            $this->initDomains();
        }
        if ('Host' == $relationName) {
            $this->initHosts();
        }
    }

    /**
     * Clears out the collClientContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Client The current object (for fluent API support)
     * @see        addClientContacts()
     */
    public function clearClientContacts()
    {
        $this->collClientContacts = null; // important to set this to null since that means it is uninitialized
        $this->collClientContactsPartial = null;

        return $this;
    }

    /**
     * reset is the collClientContacts collection loaded partially
     *
     * @return void
     */
    public function resetPartialClientContacts($v = true)
    {
        $this->collClientContactsPartial = $v;
    }

    /**
     * Initializes the collClientContacts collection.
     *
     * By default this just sets the collClientContacts collection to an empty array (like clearcollClientContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initClientContacts($overrideExisting = true)
    {
        if (null !== $this->collClientContacts && !$overrideExisting) {
            return;
        }
        $this->collClientContacts = new PropelObjectCollection();
        $this->collClientContacts->setModel('ClientContact');
    }

    /**
     * Gets an array of ClientContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Client is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ClientContact[] List of ClientContact objects
     * @throws PropelException
     */
    public function getClientContacts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collClientContactsPartial && !$this->isNew();
        if (null === $this->collClientContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collClientContacts) {
                // return empty collection
                $this->initClientContacts();
            } else {
                $collClientContacts = ClientContactQuery::create(null, $criteria)
                    ->filterByClient($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collClientContactsPartial && count($collClientContacts)) {
                      $this->initClientContacts(false);

                      foreach ($collClientContacts as $obj) {
                        if (false == $this->collClientContacts->contains($obj)) {
                          $this->collClientContacts->append($obj);
                        }
                      }

                      $this->collClientContactsPartial = true;
                    }

                    $collClientContacts->getInternalIterator()->rewind();

                    return $collClientContacts;
                }

                if ($partial && $this->collClientContacts) {
                    foreach ($this->collClientContacts as $obj) {
                        if ($obj->isNew()) {
                            $collClientContacts[] = $obj;
                        }
                    }
                }

                $this->collClientContacts = $collClientContacts;
                $this->collClientContactsPartial = false;
            }
        }

        return $this->collClientContacts;
    }

    /**
     * Sets a collection of ClientContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $clientContacts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Client The current object (for fluent API support)
     */
    public function setClientContacts(PropelCollection $clientContacts, PropelPDO $con = null)
    {
        $clientContactsToDelete = $this->getClientContacts(new Criteria(), $con)->diff($clientContacts);


        $this->clientContactsScheduledForDeletion = $clientContactsToDelete;

        foreach ($clientContactsToDelete as $clientContactRemoved) {
            $clientContactRemoved->setClient(null);
        }

        $this->collClientContacts = null;
        foreach ($clientContacts as $clientContact) {
            $this->addClientContact($clientContact);
        }

        $this->collClientContacts = $clientContacts;
        $this->collClientContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ClientContact objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ClientContact objects.
     * @throws PropelException
     */
    public function countClientContacts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collClientContactsPartial && !$this->isNew();
        if (null === $this->collClientContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collClientContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getClientContacts());
            }
            $query = ClientContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByClient($this)
                ->count($con);
        }

        return count($this->collClientContacts);
    }

    /**
     * Method called to associate a ClientContact object to this object
     * through the ClientContact foreign key attribute.
     *
     * @param    ClientContact $l ClientContact
     * @return Client The current object (for fluent API support)
     */
    public function addClientContact(ClientContact $l)
    {
        if ($this->collClientContacts === null) {
            $this->initClientContacts();
            $this->collClientContactsPartial = true;
        }

        if (!in_array($l, $this->collClientContacts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddClientContact($l);

            if ($this->clientContactsScheduledForDeletion and $this->clientContactsScheduledForDeletion->contains($l)) {
                $this->clientContactsScheduledForDeletion->remove($this->clientContactsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ClientContact $clientContact The clientContact object to add.
     */
    protected function doAddClientContact($clientContact)
    {
        $this->collClientContacts[]= $clientContact;
        $clientContact->setClient($this);
    }

    /**
     * @param	ClientContact $clientContact The clientContact object to remove.
     * @return Client The current object (for fluent API support)
     */
    public function removeClientContact($clientContact)
    {
        if ($this->getClientContacts()->contains($clientContact)) {
            $this->collClientContacts->remove($this->collClientContacts->search($clientContact));
            if (null === $this->clientContactsScheduledForDeletion) {
                $this->clientContactsScheduledForDeletion = clone $this->collClientContacts;
                $this->clientContactsScheduledForDeletion->clear();
            }
            $this->clientContactsScheduledForDeletion[]= clone $clientContact;
            $clientContact->setClient(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related ClientContacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ClientContact[] List of ClientContact objects
     */
    public function getClientContactsJoinContact($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ClientContactQuery::create(null, $criteria);
        $query->joinWith('Contact', $join_behavior);

        return $this->getClientContacts($query, $con);
    }

    /**
     * Clears out the collDomains collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Client The current object (for fluent API support)
     * @see        addDomains()
     */
    public function clearDomains()
    {
        $this->collDomains = null; // important to set this to null since that means it is uninitialized
        $this->collDomainsPartial = null;

        return $this;
    }

    /**
     * reset is the collDomains collection loaded partially
     *
     * @return void
     */
    public function resetPartialDomains($v = true)
    {
        $this->collDomainsPartial = $v;
    }

    /**
     * Initializes the collDomains collection.
     *
     * By default this just sets the collDomains collection to an empty array (like clearcollDomains());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDomains($overrideExisting = true)
    {
        if (null !== $this->collDomains && !$overrideExisting) {
            return;
        }
        $this->collDomains = new PropelObjectCollection();
        $this->collDomains->setModel('Domain');
    }

    /**
     * Gets an array of Domain objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Client is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Domain[] List of Domain objects
     * @throws PropelException
     */
    public function getDomains($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDomainsPartial && !$this->isNew();
        if (null === $this->collDomains || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDomains) {
                // return empty collection
                $this->initDomains();
            } else {
                $collDomains = DomainQuery::create(null, $criteria)
                    ->filterByClient($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDomainsPartial && count($collDomains)) {
                      $this->initDomains(false);

                      foreach ($collDomains as $obj) {
                        if (false == $this->collDomains->contains($obj)) {
                          $this->collDomains->append($obj);
                        }
                      }

                      $this->collDomainsPartial = true;
                    }

                    $collDomains->getInternalIterator()->rewind();

                    return $collDomains;
                }

                if ($partial && $this->collDomains) {
                    foreach ($this->collDomains as $obj) {
                        if ($obj->isNew()) {
                            $collDomains[] = $obj;
                        }
                    }
                }

                $this->collDomains = $collDomains;
                $this->collDomainsPartial = false;
            }
        }

        return $this->collDomains;
    }

    /**
     * Sets a collection of Domain objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $domains A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Client The current object (for fluent API support)
     */
    public function setDomains(PropelCollection $domains, PropelPDO $con = null)
    {
        $domainsToDelete = $this->getDomains(new Criteria(), $con)->diff($domains);


        $this->domainsScheduledForDeletion = $domainsToDelete;

        foreach ($domainsToDelete as $domainRemoved) {
            $domainRemoved->setClient(null);
        }

        $this->collDomains = null;
        foreach ($domains as $domain) {
            $this->addDomain($domain);
        }

        $this->collDomains = $domains;
        $this->collDomainsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Domain objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Domain objects.
     * @throws PropelException
     */
    public function countDomains(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDomainsPartial && !$this->isNew();
        if (null === $this->collDomains || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDomains) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDomains());
            }
            $query = DomainQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByClient($this)
                ->count($con);
        }

        return count($this->collDomains);
    }

    /**
     * Method called to associate a Domain object to this object
     * through the Domain foreign key attribute.
     *
     * @param    Domain $l Domain
     * @return Client The current object (for fluent API support)
     */
    public function addDomain(Domain $l)
    {
        if ($this->collDomains === null) {
            $this->initDomains();
            $this->collDomainsPartial = true;
        }

        if (!in_array($l, $this->collDomains->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDomain($l);

            if ($this->domainsScheduledForDeletion and $this->domainsScheduledForDeletion->contains($l)) {
                $this->domainsScheduledForDeletion->remove($this->domainsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Domain $domain The domain object to add.
     */
    protected function doAddDomain($domain)
    {
        $this->collDomains[]= $domain;
        $domain->setClient($this);
    }

    /**
     * @param	Domain $domain The domain object to remove.
     * @return Client The current object (for fluent API support)
     */
    public function removeDomain($domain)
    {
        if ($this->getDomains()->contains($domain)) {
            $this->collDomains->remove($this->collDomains->search($domain));
            if (null === $this->domainsScheduledForDeletion) {
                $this->domainsScheduledForDeletion = clone $this->collDomains;
                $this->domainsScheduledForDeletion->clear();
            }
            $this->domainsScheduledForDeletion[]= $domain;
            $domain->setClient(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related Domains from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Domain[] List of Domain objects
     */
    public function getDomainsJoinNameserver($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DomainQuery::create(null, $criteria);
        $query->joinWith('Nameserver', $join_behavior);

        return $this->getDomains($query, $con);
    }

    /**
     * Clears out the collHosts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Client The current object (for fluent API support)
     * @see        addHosts()
     */
    public function clearHosts()
    {
        $this->collHosts = null; // important to set this to null since that means it is uninitialized
        $this->collHostsPartial = null;

        return $this;
    }

    /**
     * reset is the collHosts collection loaded partially
     *
     * @return void
     */
    public function resetPartialHosts($v = true)
    {
        $this->collHostsPartial = $v;
    }

    /**
     * Initializes the collHosts collection.
     *
     * By default this just sets the collHosts collection to an empty array (like clearcollHosts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initHosts($overrideExisting = true)
    {
        if (null !== $this->collHosts && !$overrideExisting) {
            return;
        }
        $this->collHosts = new PropelObjectCollection();
        $this->collHosts->setModel('Host');
    }

    /**
     * Gets an array of Host objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Client is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Host[] List of Host objects
     * @throws PropelException
     */
    public function getHosts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collHostsPartial && !$this->isNew();
        if (null === $this->collHosts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collHosts) {
                // return empty collection
                $this->initHosts();
            } else {
                $collHosts = HostQuery::create(null, $criteria)
                    ->filterByClient($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collHostsPartial && count($collHosts)) {
                      $this->initHosts(false);

                      foreach ($collHosts as $obj) {
                        if (false == $this->collHosts->contains($obj)) {
                          $this->collHosts->append($obj);
                        }
                      }

                      $this->collHostsPartial = true;
                    }

                    $collHosts->getInternalIterator()->rewind();

                    return $collHosts;
                }

                if ($partial && $this->collHosts) {
                    foreach ($this->collHosts as $obj) {
                        if ($obj->isNew()) {
                            $collHosts[] = $obj;
                        }
                    }
                }

                $this->collHosts = $collHosts;
                $this->collHostsPartial = false;
            }
        }

        return $this->collHosts;
    }

    /**
     * Sets a collection of Host objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $hosts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Client The current object (for fluent API support)
     */
    public function setHosts(PropelCollection $hosts, PropelPDO $con = null)
    {
        $hostsToDelete = $this->getHosts(new Criteria(), $con)->diff($hosts);


        $this->hostsScheduledForDeletion = $hostsToDelete;

        foreach ($hostsToDelete as $hostRemoved) {
            $hostRemoved->setClient(null);
        }

        $this->collHosts = null;
        foreach ($hosts as $host) {
            $this->addHost($host);
        }

        $this->collHosts = $hosts;
        $this->collHostsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Host objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Host objects.
     * @throws PropelException
     */
    public function countHosts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collHostsPartial && !$this->isNew();
        if (null === $this->collHosts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collHosts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getHosts());
            }
            $query = HostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByClient($this)
                ->count($con);
        }

        return count($this->collHosts);
    }

    /**
     * Method called to associate a Host object to this object
     * through the Host foreign key attribute.
     *
     * @param    Host $l Host
     * @return Client The current object (for fluent API support)
     */
    public function addHost(Host $l)
    {
        if ($this->collHosts === null) {
            $this->initHosts();
            $this->collHostsPartial = true;
        }

        if (!in_array($l, $this->collHosts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddHost($l);

            if ($this->hostsScheduledForDeletion and $this->hostsScheduledForDeletion->contains($l)) {
                $this->hostsScheduledForDeletion->remove($this->hostsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Host $host The host object to add.
     */
    protected function doAddHost($host)
    {
        $this->collHosts[]= $host;
        $host->setClient($this);
    }

    /**
     * @param	Host $host The host object to remove.
     * @return Client The current object (for fluent API support)
     */
    public function removeHost($host)
    {
        if ($this->getHosts()->contains($host)) {
            $this->collHosts->remove($this->collHosts->search($host));
            if (null === $this->hostsScheduledForDeletion) {
                $this->hostsScheduledForDeletion = clone $this->collHosts;
                $this->hostsScheduledForDeletion->clear();
            }
            $this->hostsScheduledForDeletion[]= $host;
            $host->setClient(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related Hosts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsJoinHostRelatedByParentId($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('HostRelatedByParentId', $join_behavior);

        return $this->getHosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related Hosts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsJoinHostStatus($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('HostStatus', $join_behavior);

        return $this->getHosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related Hosts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsJoinHostType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('HostType', $join_behavior);

        return $this->getHosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related Hosts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsJoinLocation($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('Location', $join_behavior);

        return $this->getHosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Client is new, it will return
     * an empty collection; or if this Client has previously
     * been saved, it will retrieve related Hosts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Client.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Host[] List of Host objects
     */
    public function getHostsJoinOs($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = HostQuery::create(null, $criteria);
        $query->joinWith('Os', $join_behavior);

        return $this->getHosts($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->notes = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collClientContacts) {
                foreach ($this->collClientContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDomains) {
                foreach ($this->collDomains as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collHosts) {
                foreach ($this->collHosts as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collClientContacts instanceof PropelCollection) {
            $this->collClientContacts->clearIterator();
        }
        $this->collClientContacts = null;
        if ($this->collDomains instanceof PropelCollection) {
            $this->collDomains->clearIterator();
        }
        $this->collDomains = null;
        if ($this->collHosts instanceof PropelCollection) {
            $this->collHosts->clearIterator();
        }
        $this->collHosts = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ClientPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
