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
use LF14\SysMgmtBundle\Model\Domain;
use LF14\SysMgmtBundle\Model\DomainPeer;
use LF14\SysMgmtBundle\Model\DomainQuery;
use LF14\SysMgmtBundle\Model\Hosting;
use LF14\SysMgmtBundle\Model\Mailbox;
use LF14\SysMgmtBundle\Model\Nameserver;

/**
 * @method DomainQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DomainQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DomainQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 * @method DomainQuery orderByDateStart($order = Criteria::ASC) Order by the date_start column
 * @method DomainQuery orderByDateEnd($order = Criteria::ASC) Order by the date_end column
 * @method DomainQuery orderByClientId($order = Criteria::ASC) Order by the client_id column
 * @method DomainQuery orderByNameserverId($order = Criteria::ASC) Order by the nameserver_id column
 * @method DomainQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method DomainQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method DomainQuery groupById() Group by the id column
 * @method DomainQuery groupByName() Group by the name column
 * @method DomainQuery groupByNotes() Group by the notes column
 * @method DomainQuery groupByDateStart() Group by the date_start column
 * @method DomainQuery groupByDateEnd() Group by the date_end column
 * @method DomainQuery groupByClientId() Group by the client_id column
 * @method DomainQuery groupByNameserverId() Group by the nameserver_id column
 * @method DomainQuery groupByCreatedAt() Group by the created_at column
 * @method DomainQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method DomainQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DomainQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DomainQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DomainQuery leftJoinClient($relationAlias = null) Adds a LEFT JOIN clause to the query using the Client relation
 * @method DomainQuery rightJoinClient($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Client relation
 * @method DomainQuery innerJoinClient($relationAlias = null) Adds a INNER JOIN clause to the query using the Client relation
 *
 * @method DomainQuery leftJoinNameserver($relationAlias = null) Adds a LEFT JOIN clause to the query using the Nameserver relation
 * @method DomainQuery rightJoinNameserver($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Nameserver relation
 * @method DomainQuery innerJoinNameserver($relationAlias = null) Adds a INNER JOIN clause to the query using the Nameserver relation
 *
 * @method DomainQuery leftJoinHosting($relationAlias = null) Adds a LEFT JOIN clause to the query using the Hosting relation
 * @method DomainQuery rightJoinHosting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Hosting relation
 * @method DomainQuery innerJoinHosting($relationAlias = null) Adds a INNER JOIN clause to the query using the Hosting relation
 *
 * @method DomainQuery leftJoinMailbox($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mailbox relation
 * @method DomainQuery rightJoinMailbox($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mailbox relation
 * @method DomainQuery innerJoinMailbox($relationAlias = null) Adds a INNER JOIN clause to the query using the Mailbox relation
 *
 * @method Domain findOne(PropelPDO $con = null) Return the first Domain matching the query
 * @method Domain findOneOrCreate(PropelPDO $con = null) Return the first Domain matching the query, or a new Domain object populated from the query conditions when no match is found
 *
 * @method Domain findOneByName(string $name) Return the first Domain filtered by the name column
 * @method Domain findOneByNotes(string $notes) Return the first Domain filtered by the notes column
 * @method Domain findOneByDateStart(string $date_start) Return the first Domain filtered by the date_start column
 * @method Domain findOneByDateEnd(string $date_end) Return the first Domain filtered by the date_end column
 * @method Domain findOneByClientId(int $client_id) Return the first Domain filtered by the client_id column
 * @method Domain findOneByNameserverId(int $nameserver_id) Return the first Domain filtered by the nameserver_id column
 * @method Domain findOneByCreatedAt(string $created_at) Return the first Domain filtered by the created_at column
 * @method Domain findOneByUpdatedAt(string $updated_at) Return the first Domain filtered by the updated_at column
 *
 * @method array findById(int $id) Return Domain objects filtered by the id column
 * @method array findByName(string $name) Return Domain objects filtered by the name column
 * @method array findByNotes(string $notes) Return Domain objects filtered by the notes column
 * @method array findByDateStart(string $date_start) Return Domain objects filtered by the date_start column
 * @method array findByDateEnd(string $date_end) Return Domain objects filtered by the date_end column
 * @method array findByClientId(int $client_id) Return Domain objects filtered by the client_id column
 * @method array findByNameserverId(int $nameserver_id) Return Domain objects filtered by the nameserver_id column
 * @method array findByCreatedAt(string $created_at) Return Domain objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Domain objects filtered by the updated_at column
 */
abstract class BaseDomainQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDomainQuery object.
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
            $modelName = 'LF14\\SysMgmtBundle\\Model\\Domain';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DomainQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DomainQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DomainQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DomainQuery) {
            return $criteria;
        }
        $query = new DomainQuery(null, null, $modelAlias);

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
     * @return   Domain|Domain[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DomainPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DomainPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Domain A model object, or null if the key is not found
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
     * @return                 Domain A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `notes`, `date_start`, `date_end`, `client_id`, `nameserver_id`, `created_at`, `updated_at` FROM `domain` WHERE `id` = :p0';
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
            $obj = new Domain();
            $obj->hydrate($row);
            DomainPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Domain|Domain[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Domain[]|mixed the list of results, formatted by the current formatter
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
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DomainPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DomainPeer::ID, $keys, Criteria::IN);
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
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DomainPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DomainPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::ID, $id, $comparison);
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
     * @return DomainQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DomainPeer::NAME, $name, $comparison);
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
     * @return DomainQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DomainPeer::NOTES, $notes, $comparison);
    }

    /**
     * Filter the query on the date_start column
     *
     * Example usage:
     * <code>
     * $query->filterByDateStart('2011-03-14'); // WHERE date_start = '2011-03-14'
     * $query->filterByDateStart('now'); // WHERE date_start = '2011-03-14'
     * $query->filterByDateStart(array('max' => 'yesterday')); // WHERE date_start < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateStart The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByDateStart($dateStart = null, $comparison = null)
    {
        if (is_array($dateStart)) {
            $useMinMax = false;
            if (isset($dateStart['min'])) {
                $this->addUsingAlias(DomainPeer::DATE_START, $dateStart['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateStart['max'])) {
                $this->addUsingAlias(DomainPeer::DATE_START, $dateStart['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::DATE_START, $dateStart, $comparison);
    }

    /**
     * Filter the query on the date_end column
     *
     * Example usage:
     * <code>
     * $query->filterByDateEnd('2011-03-14'); // WHERE date_end = '2011-03-14'
     * $query->filterByDateEnd('now'); // WHERE date_end = '2011-03-14'
     * $query->filterByDateEnd(array('max' => 'yesterday')); // WHERE date_end < '2011-03-13'
     * </code>
     *
     * @param     mixed $dateEnd The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByDateEnd($dateEnd = null, $comparison = null)
    {
        if (is_array($dateEnd)) {
            $useMinMax = false;
            if (isset($dateEnd['min'])) {
                $this->addUsingAlias(DomainPeer::DATE_END, $dateEnd['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateEnd['max'])) {
                $this->addUsingAlias(DomainPeer::DATE_END, $dateEnd['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::DATE_END, $dateEnd, $comparison);
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
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByClientId($clientId = null, $comparison = null)
    {
        if (is_array($clientId)) {
            $useMinMax = false;
            if (isset($clientId['min'])) {
                $this->addUsingAlias(DomainPeer::CLIENT_ID, $clientId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($clientId['max'])) {
                $this->addUsingAlias(DomainPeer::CLIENT_ID, $clientId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::CLIENT_ID, $clientId, $comparison);
    }

    /**
     * Filter the query on the nameserver_id column
     *
     * Example usage:
     * <code>
     * $query->filterByNameserverId(1234); // WHERE nameserver_id = 1234
     * $query->filterByNameserverId(array(12, 34)); // WHERE nameserver_id IN (12, 34)
     * $query->filterByNameserverId(array('min' => 12)); // WHERE nameserver_id >= 12
     * $query->filterByNameserverId(array('max' => 12)); // WHERE nameserver_id <= 12
     * </code>
     *
     * @see       filterByNameserver()
     *
     * @param     mixed $nameserverId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByNameserverId($nameserverId = null, $comparison = null)
    {
        if (is_array($nameserverId)) {
            $useMinMax = false;
            if (isset($nameserverId['min'])) {
                $this->addUsingAlias(DomainPeer::NAMESERVER_ID, $nameserverId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nameserverId['max'])) {
                $this->addUsingAlias(DomainPeer::NAMESERVER_ID, $nameserverId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::NAMESERVER_ID, $nameserverId, $comparison);
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
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(DomainPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(DomainPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return DomainQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(DomainPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(DomainPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Client object
     *
     * @param   Client|PropelObjectCollection $client The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DomainQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByClient($client, $comparison = null)
    {
        if ($client instanceof Client) {
            return $this
                ->addUsingAlias(DomainPeer::CLIENT_ID, $client->getId(), $comparison);
        } elseif ($client instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DomainPeer::CLIENT_ID, $client->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DomainQuery The current query, for fluid interface
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
     * Filter the query by a related Nameserver object
     *
     * @param   Nameserver|PropelObjectCollection $nameserver The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DomainQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByNameserver($nameserver, $comparison = null)
    {
        if ($nameserver instanceof Nameserver) {
            return $this
                ->addUsingAlias(DomainPeer::NAMESERVER_ID, $nameserver->getId(), $comparison);
        } elseif ($nameserver instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DomainPeer::NAMESERVER_ID, $nameserver->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByNameserver() only accepts arguments of type Nameserver or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Nameserver relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function joinNameserver($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Nameserver');

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
            $this->addJoinObject($join, 'Nameserver');
        }

        return $this;
    }

    /**
     * Use the Nameserver relation Nameserver object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\NameserverQuery A secondary query class using the current class as primary query
     */
    public function useNameserverQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinNameserver($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Nameserver', '\LF14\SysMgmtBundle\Model\NameserverQuery');
    }

    /**
     * Filter the query by a related Hosting object
     *
     * @param   Hosting|PropelObjectCollection $hosting  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DomainQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHosting($hosting, $comparison = null)
    {
        if ($hosting instanceof Hosting) {
            return $this
                ->addUsingAlias(DomainPeer::ID, $hosting->getDomainId(), $comparison);
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
     * @return DomainQuery The current query, for fluid interface
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
     * Filter the query by a related Mailbox object
     *
     * @param   Mailbox|PropelObjectCollection $mailbox  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DomainQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMailbox($mailbox, $comparison = null)
    {
        if ($mailbox instanceof Mailbox) {
            return $this
                ->addUsingAlias(DomainPeer::ID, $mailbox->getDomainId(), $comparison);
        } elseif ($mailbox instanceof PropelObjectCollection) {
            return $this
                ->useMailboxQuery()
                ->filterByPrimaryKeys($mailbox->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMailbox() only accepts arguments of type Mailbox or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Mailbox relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function joinMailbox($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Mailbox');

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
            $this->addJoinObject($join, 'Mailbox');
        }

        return $this;
    }

    /**
     * Use the Mailbox relation Mailbox object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\MailboxQuery A secondary query class using the current class as primary query
     */
    public function useMailboxQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMailbox($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Mailbox', '\LF14\SysMgmtBundle\Model\MailboxQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Domain $domain Object to remove from the list of results
     *
     * @return DomainQuery The current query, for fluid interface
     */
    public function prune($domain = null)
    {
        if ($domain) {
            $this->addUsingAlias(DomainPeer::ID, $domain->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
