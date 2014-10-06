<?php

namespace LF14\SysMgmtBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LF14\SysMgmtBundle\Model\Client;
use LF14\SysMgmtBundle\Model\Host;
use LF14\SysMgmtBundle\Model\HostIp;
use LF14\SysMgmtBundle\Model\HostPeer;
use LF14\SysMgmtBundle\Model\HostQuery;
use LF14\SysMgmtBundle\Model\HostStatus;
use LF14\SysMgmtBundle\Model\HostType;
use LF14\SysMgmtBundle\Model\Hosting;
use LF14\SysMgmtBundle\Model\Location;
use LF14\SysMgmtBundle\Model\Login;
use LF14\SysMgmtBundle\Model\Os;

/**
 * @method HostQuery orderById($order = Criteria::ASC) Order by the id column
 * @method HostQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method HostQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method HostQuery orderByClientId($order = Criteria::ASC) Order by the client_id column
 * @method HostQuery orderByLocationId($order = Criteria::ASC) Order by the location_id column
 * @method HostQuery orderByHostTypeId($order = Criteria::ASC) Order by the host_type_id column
 * @method HostQuery orderByHostStatusId($order = Criteria::ASC) Order by the host_status_id column
 * @method HostQuery orderByOsId($order = Criteria::ASC) Order by the os_id column
 * @method HostQuery orderByParentId($order = Criteria::ASC) Order by the parent_id column
 * @method HostQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method HostQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method HostQuery groupById() Group by the id column
 * @method HostQuery groupByName() Group by the name column
 * @method HostQuery groupByNotes() Group by the notes column
 * @method HostQuery groupByClientId() Group by the client_id column
 * @method HostQuery groupByLocationId() Group by the location_id column
 * @method HostQuery groupByHostTypeId() Group by the host_type_id column
 * @method HostQuery groupByHostStatusId() Group by the host_status_id column
 * @method HostQuery groupByOsId() Group by the os_id column
 * @method HostQuery groupByParentId() Group by the parent_id column
 * @method HostQuery groupByCreatedAt() Group by the created_at column
 * @method HostQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method HostQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method HostQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method HostQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method HostQuery leftJoinClient($relationAlias = null) Adds a LEFT JOIN clause to the query using the Client relation
 * @method HostQuery rightJoinClient($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Client relation
 * @method HostQuery innerJoinClient($relationAlias = null) Adds a INNER JOIN clause to the query using the Client relation
 *
 * @method HostQuery leftJoinHostRelatedByParentId($relationAlias = null) Adds a LEFT JOIN clause to the query using the HostRelatedByParentId relation
 * @method HostQuery rightJoinHostRelatedByParentId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HostRelatedByParentId relation
 * @method HostQuery innerJoinHostRelatedByParentId($relationAlias = null) Adds a INNER JOIN clause to the query using the HostRelatedByParentId relation
 *
 * @method HostQuery leftJoinHostStatus($relationAlias = null) Adds a LEFT JOIN clause to the query using the HostStatus relation
 * @method HostQuery rightJoinHostStatus($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HostStatus relation
 * @method HostQuery innerJoinHostStatus($relationAlias = null) Adds a INNER JOIN clause to the query using the HostStatus relation
 *
 * @method HostQuery leftJoinHostType($relationAlias = null) Adds a LEFT JOIN clause to the query using the HostType relation
 * @method HostQuery rightJoinHostType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HostType relation
 * @method HostQuery innerJoinHostType($relationAlias = null) Adds a INNER JOIN clause to the query using the HostType relation
 *
 * @method HostQuery leftJoinLocation($relationAlias = null) Adds a LEFT JOIN clause to the query using the Location relation
 * @method HostQuery rightJoinLocation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Location relation
 * @method HostQuery innerJoinLocation($relationAlias = null) Adds a INNER JOIN clause to the query using the Location relation
 *
 * @method HostQuery leftJoinOs($relationAlias = null) Adds a LEFT JOIN clause to the query using the Os relation
 * @method HostQuery rightJoinOs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Os relation
 * @method HostQuery innerJoinOs($relationAlias = null) Adds a INNER JOIN clause to the query using the Os relation
 *
 * @method HostQuery leftJoinHostRelatedById($relationAlias = null) Adds a LEFT JOIN clause to the query using the HostRelatedById relation
 * @method HostQuery rightJoinHostRelatedById($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HostRelatedById relation
 * @method HostQuery innerJoinHostRelatedById($relationAlias = null) Adds a INNER JOIN clause to the query using the HostRelatedById relation
 *
 * @method HostQuery leftJoinHostIp($relationAlias = null) Adds a LEFT JOIN clause to the query using the HostIp relation
 * @method HostQuery rightJoinHostIp($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HostIp relation
 * @method HostQuery innerJoinHostIp($relationAlias = null) Adds a INNER JOIN clause to the query using the HostIp relation
 *
 * @method HostQuery leftJoinHosting($relationAlias = null) Adds a LEFT JOIN clause to the query using the Hosting relation
 * @method HostQuery rightJoinHosting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Hosting relation
 * @method HostQuery innerJoinHosting($relationAlias = null) Adds a INNER JOIN clause to the query using the Hosting relation
 *
 * @method HostQuery leftJoinLogin($relationAlias = null) Adds a LEFT JOIN clause to the query using the Login relation
 * @method HostQuery rightJoinLogin($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Login relation
 * @method HostQuery innerJoinLogin($relationAlias = null) Adds a INNER JOIN clause to the query using the Login relation
 *
 * @method Host findOne(PropelPDO $con = null) Return the first Host matching the query
 * @method Host findOneOrCreate(PropelPDO $con = null) Return the first Host matching the query, or a new Host object populated from the query conditions when no match is found
 *
 * @method Host findOneByName(string $name) Return the first Host filtered by the name column
 * @method Host findOneByNotes(string $notes) Return the first Host filtered by the notes column
 * @method Host findOneByClientId(int $client_id) Return the first Host filtered by the client_id column
 * @method Host findOneByLocationId(int $location_id) Return the first Host filtered by the location_id column
 * @method Host findOneByHostTypeId(int $host_type_id) Return the first Host filtered by the host_type_id column
 * @method Host findOneByHostStatusId(int $host_status_id) Return the first Host filtered by the host_status_id column
 * @method Host findOneByOsId(int $os_id) Return the first Host filtered by the os_id column
 * @method Host findOneByParentId(int $parent_id) Return the first Host filtered by the parent_id column
 * @method Host findOneByCreatedAt(string $created_at) Return the first Host filtered by the created_at column
 * @method Host findOneByUpdatedAt(string $updated_at) Return the first Host filtered by the updated_at column
 *
 * @method array findById(int $id) Return Host objects filtered by the id column
 * @method array findByName(string $name) Return Host objects filtered by the name column
 * @method array findByNotes(string $notes) Return Host objects filtered by the notes column
 * @method array findByClientId(int $client_id) Return Host objects filtered by the client_id column
 * @method array findByLocationId(int $location_id) Return Host objects filtered by the location_id column
 * @method array findByHostTypeId(int $host_type_id) Return Host objects filtered by the host_type_id column
 * @method array findByHostStatusId(int $host_status_id) Return Host objects filtered by the host_status_id column
 * @method array findByOsId(int $os_id) Return Host objects filtered by the os_id column
 * @method array findByParentId(int $parent_id) Return Host objects filtered by the parent_id column
 * @method array findByCreatedAt(string $created_at) Return Host objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Host objects filtered by the updated_at column
 */
abstract class BaseHostQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseHostQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'LF14\\SysMgmtBundle\\Model\\Host';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new HostQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   HostQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return HostQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof HostQuery) {
            return $criteria;
        }
        $query = new HostQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Host|Host[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = HostPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(HostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Host A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Host A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `notes`, `client_id`, `location_id`, `host_type_id`, `host_status_id`, `os_id`, `parent_id`, `created_at`, `updated_at` FROM `host` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Host();
            $obj->hydrate($row);
            HostPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Host|Host[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Host[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(HostPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(HostPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(HostPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(HostPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(HostPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the notes column
     *
     * Example usage:
     * <code>
     * $query->filterByNotes('fooValue');   // WHERE notes = 'fooValue'
     * $query->filterByNotes('%fooValue%'); // WHERE notes LIKE '%fooValue%'
     * </code>
     *
     * @param     string $notes The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByNotes($notes = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $notes)) {
                $notes = str_replace('*', '%', $notes);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(HostPeer::NOTES, $notes, $comparison);
    }

    /**
     * Filter the query on the client_id column
     *
     * Example usage:
     * <code>
     * $query->filterByClientId(1234); // WHERE client_id = 1234
     * $query->filterByClientId(array(12, 34)); // WHERE client_id IN (12, 34)
     * $query->filterByClientId(array('min' => 12)); // WHERE client_id >= 12
     * $query->filterByClientId(array('max' => 12)); // WHERE client_id <= 12
     * </code>
     *
     * @see       filterByClient()
     *
     * @param     mixed $clientId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByClientId($clientId = null, $comparison = null)
    {
        if (is_array($clientId)) {
            $useMinMax = false;
            if (isset($clientId['min'])) {
                $this->addUsingAlias(HostPeer::CLIENT_ID, $clientId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($clientId['max'])) {
                $this->addUsingAlias(HostPeer::CLIENT_ID, $clientId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::CLIENT_ID, $clientId, $comparison);
    }

    /**
     * Filter the query on the location_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLocationId(1234); // WHERE location_id = 1234
     * $query->filterByLocationId(array(12, 34)); // WHERE location_id IN (12, 34)
     * $query->filterByLocationId(array('min' => 12)); // WHERE location_id >= 12
     * $query->filterByLocationId(array('max' => 12)); // WHERE location_id <= 12
     * </code>
     *
     * @see       filterByLocation()
     *
     * @param     mixed $locationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByLocationId($locationId = null, $comparison = null)
    {
        if (is_array($locationId)) {
            $useMinMax = false;
            if (isset($locationId['min'])) {
                $this->addUsingAlias(HostPeer::LOCATION_ID, $locationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($locationId['max'])) {
                $this->addUsingAlias(HostPeer::LOCATION_ID, $locationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::LOCATION_ID, $locationId, $comparison);
    }

    /**
     * Filter the query on the host_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHostTypeId(1234); // WHERE host_type_id = 1234
     * $query->filterByHostTypeId(array(12, 34)); // WHERE host_type_id IN (12, 34)
     * $query->filterByHostTypeId(array('min' => 12)); // WHERE host_type_id >= 12
     * $query->filterByHostTypeId(array('max' => 12)); // WHERE host_type_id <= 12
     * </code>
     *
     * @see       filterByHostType()
     *
     * @param     mixed $hostTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByHostTypeId($hostTypeId = null, $comparison = null)
    {
        if (is_array($hostTypeId)) {
            $useMinMax = false;
            if (isset($hostTypeId['min'])) {
                $this->addUsingAlias(HostPeer::HOST_TYPE_ID, $hostTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hostTypeId['max'])) {
                $this->addUsingAlias(HostPeer::HOST_TYPE_ID, $hostTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::HOST_TYPE_ID, $hostTypeId, $comparison);
    }

    /**
     * Filter the query on the host_status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHostStatusId(1234); // WHERE host_status_id = 1234
     * $query->filterByHostStatusId(array(12, 34)); // WHERE host_status_id IN (12, 34)
     * $query->filterByHostStatusId(array('min' => 12)); // WHERE host_status_id >= 12
     * $query->filterByHostStatusId(array('max' => 12)); // WHERE host_status_id <= 12
     * </code>
     *
     * @see       filterByHostStatus()
     *
     * @param     mixed $hostStatusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByHostStatusId($hostStatusId = null, $comparison = null)
    {
        if (is_array($hostStatusId)) {
            $useMinMax = false;
            if (isset($hostStatusId['min'])) {
                $this->addUsingAlias(HostPeer::HOST_STATUS_ID, $hostStatusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hostStatusId['max'])) {
                $this->addUsingAlias(HostPeer::HOST_STATUS_ID, $hostStatusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::HOST_STATUS_ID, $hostStatusId, $comparison);
    }

    /**
     * Filter the query on the os_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOsId(1234); // WHERE os_id = 1234
     * $query->filterByOsId(array(12, 34)); // WHERE os_id IN (12, 34)
     * $query->filterByOsId(array('min' => 12)); // WHERE os_id >= 12
     * $query->filterByOsId(array('max' => 12)); // WHERE os_id <= 12
     * </code>
     *
     * @see       filterByOs()
     *
     * @param     mixed $osId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByOsId($osId = null, $comparison = null)
    {
        if (is_array($osId)) {
            $useMinMax = false;
            if (isset($osId['min'])) {
                $this->addUsingAlias(HostPeer::OS_ID, $osId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($osId['max'])) {
                $this->addUsingAlias(HostPeer::OS_ID, $osId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::OS_ID, $osId, $comparison);
    }

    /**
     * Filter the query on the parent_id column
     *
     * Example usage:
     * <code>
     * $query->filterByParentId(1234); // WHERE parent_id = 1234
     * $query->filterByParentId(array(12, 34)); // WHERE parent_id IN (12, 34)
     * $query->filterByParentId(array('min' => 12)); // WHERE parent_id >= 12
     * $query->filterByParentId(array('max' => 12)); // WHERE parent_id <= 12
     * </code>
     *
     * @see       filterByHostRelatedByParentId()
     *
     * @param     mixed $parentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByParentId($parentId = null, $comparison = null)
    {
        if (is_array($parentId)) {
            $useMinMax = false;
            if (isset($parentId['min'])) {
                $this->addUsingAlias(HostPeer::PARENT_ID, $parentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($parentId['max'])) {
                $this->addUsingAlias(HostPeer::PARENT_ID, $parentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::PARENT_ID, $parentId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(HostPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(HostPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(HostPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(HostPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Client object
     *
     * @param   Client|PropelObjectCollection $client The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByClient($client, $comparison = null)
    {
        if ($client instanceof Client) {
            return $this
                ->addUsingAlias(HostPeer::CLIENT_ID, $client->getId(), $comparison);
        } elseif ($client instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostPeer::CLIENT_ID, $client->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByClient() only accepts arguments of type Client or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Client relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinClient($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Client');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Client');
        }

        return $this;
    }

    /**
     * Use the Client relation Client object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\ClientQuery A secondary query class using the current class as primary query
     */
    public function useClientQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinClient($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Client', '\LF14\SysMgmtBundle\Model\ClientQuery');
    }

    /**
     * Filter the query by a related Host object
     *
     * @param   Host|PropelObjectCollection $host The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHostRelatedByParentId($host, $comparison = null)
    {
        if ($host instanceof Host) {
            return $this
                ->addUsingAlias(HostPeer::PARENT_ID, $host->getId(), $comparison);
        } elseif ($host instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostPeer::PARENT_ID, $host->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByHostRelatedByParentId() only accepts arguments of type Host or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HostRelatedByParentId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinHostRelatedByParentId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HostRelatedByParentId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'HostRelatedByParentId');
        }

        return $this;
    }

    /**
     * Use the HostRelatedByParentId relation Host object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostQuery A secondary query class using the current class as primary query
     */
    public function useHostRelatedByParentIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHostRelatedByParentId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HostRelatedByParentId', '\LF14\SysMgmtBundle\Model\HostQuery');
    }

    /**
     * Filter the query by a related HostStatus object
     *
     * @param   HostStatus|PropelObjectCollection $hostStatus The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHostStatus($hostStatus, $comparison = null)
    {
        if ($hostStatus instanceof HostStatus) {
            return $this
                ->addUsingAlias(HostPeer::HOST_STATUS_ID, $hostStatus->getId(), $comparison);
        } elseif ($hostStatus instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostPeer::HOST_STATUS_ID, $hostStatus->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByHostStatus() only accepts arguments of type HostStatus or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HostStatus relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinHostStatus($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HostStatus');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'HostStatus');
        }

        return $this;
    }

    /**
     * Use the HostStatus relation HostStatus object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostStatusQuery A secondary query class using the current class as primary query
     */
    public function useHostStatusQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHostStatus($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HostStatus', '\LF14\SysMgmtBundle\Model\HostStatusQuery');
    }

    /**
     * Filter the query by a related HostType object
     *
     * @param   HostType|PropelObjectCollection $hostType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHostType($hostType, $comparison = null)
    {
        if ($hostType instanceof HostType) {
            return $this
                ->addUsingAlias(HostPeer::HOST_TYPE_ID, $hostType->getId(), $comparison);
        } elseif ($hostType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostPeer::HOST_TYPE_ID, $hostType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByHostType() only accepts arguments of type HostType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HostType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinHostType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HostType');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'HostType');
        }

        return $this;
    }

    /**
     * Use the HostType relation HostType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostTypeQuery A secondary query class using the current class as primary query
     */
    public function useHostTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHostType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HostType', '\LF14\SysMgmtBundle\Model\HostTypeQuery');
    }

    /**
     * Filter the query by a related Location object
     *
     * @param   Location|PropelObjectCollection $location The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLocation($location, $comparison = null)
    {
        if ($location instanceof Location) {
            return $this
                ->addUsingAlias(HostPeer::LOCATION_ID, $location->getId(), $comparison);
        } elseif ($location instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostPeer::LOCATION_ID, $location->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLocation() only accepts arguments of type Location or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Location relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinLocation($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Location');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Location');
        }

        return $this;
    }

    /**
     * Use the Location relation Location object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\LocationQuery A secondary query class using the current class as primary query
     */
    public function useLocationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLocation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Location', '\LF14\SysMgmtBundle\Model\LocationQuery');
    }

    /**
     * Filter the query by a related Os object
     *
     * @param   Os|PropelObjectCollection $os The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByOs($os, $comparison = null)
    {
        if ($os instanceof Os) {
            return $this
                ->addUsingAlias(HostPeer::OS_ID, $os->getId(), $comparison);
        } elseif ($os instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostPeer::OS_ID, $os->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOs() only accepts arguments of type Os or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Os relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinOs($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Os');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Os');
        }

        return $this;
    }

    /**
     * Use the Os relation Os object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\OsQuery A secondary query class using the current class as primary query
     */
    public function useOsQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Os', '\LF14\SysMgmtBundle\Model\OsQuery');
    }

    /**
     * Filter the query by a related Host object
     *
     * @param   Host|PropelObjectCollection $host  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHostRelatedById($host, $comparison = null)
    {
        if ($host instanceof Host) {
            return $this
                ->addUsingAlias(HostPeer::ID, $host->getParentId(), $comparison);
        } elseif ($host instanceof PropelObjectCollection) {
            return $this
                ->useHostRelatedByIdQuery()
                ->filterByPrimaryKeys($host->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByHostRelatedById() only accepts arguments of type Host or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HostRelatedById relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinHostRelatedById($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HostRelatedById');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'HostRelatedById');
        }

        return $this;
    }

    /**
     * Use the HostRelatedById relation Host object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostQuery A secondary query class using the current class as primary query
     */
    public function useHostRelatedByIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHostRelatedById($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HostRelatedById', '\LF14\SysMgmtBundle\Model\HostQuery');
    }

    /**
     * Filter the query by a related HostIp object
     *
     * @param   HostIp|PropelObjectCollection $hostIp  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHostIp($hostIp, $comparison = null)
    {
        if ($hostIp instanceof HostIp) {
            return $this
                ->addUsingAlias(HostPeer::ID, $hostIp->getHostId(), $comparison);
        } elseif ($hostIp instanceof PropelObjectCollection) {
            return $this
                ->useHostIpQuery()
                ->filterByPrimaryKeys($hostIp->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByHostIp() only accepts arguments of type HostIp or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HostIp relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinHostIp($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HostIp');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'HostIp');
        }

        return $this;
    }

    /**
     * Use the HostIp relation HostIp object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostIpQuery A secondary query class using the current class as primary query
     */
    public function useHostIpQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinHostIp($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HostIp', '\LF14\SysMgmtBundle\Model\HostIpQuery');
    }

    /**
     * Filter the query by a related Hosting object
     *
     * @param   Hosting|PropelObjectCollection $hosting  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHosting($hosting, $comparison = null)
    {
        if ($hosting instanceof Hosting) {
            return $this
                ->addUsingAlias(HostPeer::ID, $hosting->getHostId(), $comparison);
        } elseif ($hosting instanceof PropelObjectCollection) {
            return $this
                ->useHostingQuery()
                ->filterByPrimaryKeys($hosting->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByHosting() only accepts arguments of type Hosting or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Hosting relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinHosting($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Hosting');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Hosting');
        }

        return $this;
    }

    /**
     * Use the Hosting relation Hosting object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostingQuery A secondary query class using the current class as primary query
     */
    public function useHostingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHosting($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Hosting', '\LF14\SysMgmtBundle\Model\HostingQuery');
    }

    /**
     * Filter the query by a related Login object
     *
     * @param   Login|PropelObjectCollection $login  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByLogin($login, $comparison = null)
    {
        if ($login instanceof Login) {
            return $this
                ->addUsingAlias(HostPeer::ID, $login->getHostId(), $comparison);
        } elseif ($login instanceof PropelObjectCollection) {
            return $this
                ->useLoginQuery()
                ->filterByPrimaryKeys($login->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLogin() only accepts arguments of type Login or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Login relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function joinLogin($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Login');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Login');
        }

        return $this;
    }

    /**
     * Use the Login relation Login object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\LoginQuery A secondary query class using the current class as primary query
     */
    public function useLoginQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLogin($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Login', '\LF14\SysMgmtBundle\Model\LoginQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Host $host Object to remove from the list of results
     *
     * @return HostQuery The current query, for fluid interface
     */
    public function prune($host = null)
    {
        if ($host) {
            $this->addUsingAlias(HostPeer::ID, $host->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
