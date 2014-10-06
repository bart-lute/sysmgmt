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
use LF14\SysMgmtBundle\Model\Host;
use LF14\SysMgmtBundle\Model\HostIp;
use LF14\SysMgmtBundle\Model\HostIpPeer;
use LF14\SysMgmtBundle\Model\HostIpQuery;

/**
 * @method HostIpQuery orderById($order = Criteria::ASC) Order by the id column
 * @method HostIpQuery orderByHostId($order = Criteria::ASC) Order by the host_id column
 * @method HostIpQuery orderByIpv4($order = Criteria::ASC) Order by the ipv4 column
 * @method HostIpQuery orderByIpv6($order = Criteria::ASC) Order by the ipv6 column
 * @method HostIpQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method HostIpQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method HostIpQuery groupById() Group by the id column
 * @method HostIpQuery groupByHostId() Group by the host_id column
 * @method HostIpQuery groupByIpv4() Group by the ipv4 column
 * @method HostIpQuery groupByIpv6() Group by the ipv6 column
 * @method HostIpQuery groupByCreatedAt() Group by the created_at column
 * @method HostIpQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method HostIpQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method HostIpQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method HostIpQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method HostIpQuery leftJoinHost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Host relation
 * @method HostIpQuery rightJoinHost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Host relation
 * @method HostIpQuery innerJoinHost($relationAlias = null) Adds a INNER JOIN clause to the query using the Host relation
 *
 * @method HostIp findOne(PropelPDO $con = null) Return the first HostIp matching the query
 * @method HostIp findOneOrCreate(PropelPDO $con = null) Return the first HostIp matching the query, or a new HostIp object populated from the query conditions when no match is found
 *
 * @method HostIp findOneByHostId(int $host_id) Return the first HostIp filtered by the host_id column
 * @method HostIp findOneByIpv4(string $ipv4) Return the first HostIp filtered by the ipv4 column
 * @method HostIp findOneByIpv6(string $ipv6) Return the first HostIp filtered by the ipv6 column
 * @method HostIp findOneByCreatedAt(string $created_at) Return the first HostIp filtered by the created_at column
 * @method HostIp findOneByUpdatedAt(string $updated_at) Return the first HostIp filtered by the updated_at column
 *
 * @method array findById(int $id) Return HostIp objects filtered by the id column
 * @method array findByHostId(int $host_id) Return HostIp objects filtered by the host_id column
 * @method array findByIpv4(string $ipv4) Return HostIp objects filtered by the ipv4 column
 * @method array findByIpv6(string $ipv6) Return HostIp objects filtered by the ipv6 column
 * @method array findByCreatedAt(string $created_at) Return HostIp objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return HostIp objects filtered by the updated_at column
 */
abstract class BaseHostIpQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseHostIpQuery object.
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
            $modelName = 'LF14\\SysMgmtBundle\\Model\\HostIp';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new HostIpQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   HostIpQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return HostIpQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof HostIpQuery) {
            return $criteria;
        }
        $query = new HostIpQuery(null, null, $modelAlias);

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
     * @return   HostIp|HostIp[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = HostIpPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(HostIpPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 HostIp A model object, or null if the key is not found
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
     * @return                 HostIp A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `host_id`, `ipv4`, `ipv6`, `created_at`, `updated_at` FROM `host_ip` WHERE `id` = :p0';
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
            $obj = new HostIp();
            $obj->hydrate($row);
            HostIpPeer::addInstanceToPool($obj, (string) $key);
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
     * @return HostIp|HostIp[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|HostIp[]|mixed the list of results, formatted by the current formatter
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
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(HostIpPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(HostIpPeer::ID, $keys, Criteria::IN);
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
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(HostIpPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(HostIpPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostIpPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the host_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHostId(1234); // WHERE host_id = 1234
     * $query->filterByHostId(array(12, 34)); // WHERE host_id IN (12, 34)
     * $query->filterByHostId(array('min' => 12)); // WHERE host_id >= 12
     * $query->filterByHostId(array('max' => 12)); // WHERE host_id <= 12
     * </code>
     *
     * @see       filterByHost()
     *
     * @param     mixed $hostId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByHostId($hostId = null, $comparison = null)
    {
        if (is_array($hostId)) {
            $useMinMax = false;
            if (isset($hostId['min'])) {
                $this->addUsingAlias(HostIpPeer::HOST_ID, $hostId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($hostId['max'])) {
                $this->addUsingAlias(HostIpPeer::HOST_ID, $hostId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostIpPeer::HOST_ID, $hostId, $comparison);
    }

    /**
     * Filter the query on the ipv4 column
     *
     * Example usage:
     * <code>
     * $query->filterByIpv4('fooValue');   // WHERE ipv4 = 'fooValue'
     * $query->filterByIpv4('%fooValue%'); // WHERE ipv4 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ipv4 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByIpv4($ipv4 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ipv4)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ipv4)) {
                $ipv4 = str_replace('*', '%', $ipv4);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(HostIpPeer::IPV4, $ipv4, $comparison);
    }

    /**
     * Filter the query on the ipv6 column
     *
     * Example usage:
     * <code>
     * $query->filterByIpv6('fooValue');   // WHERE ipv6 = 'fooValue'
     * $query->filterByIpv6('%fooValue%'); // WHERE ipv6 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ipv6 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByIpv6($ipv6 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ipv6)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ipv6)) {
                $ipv6 = str_replace('*', '%', $ipv6);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(HostIpPeer::IPV6, $ipv6, $comparison);
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
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(HostIpPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(HostIpPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostIpPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return HostIpQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(HostIpPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(HostIpPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(HostIpPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Host object
     *
     * @param   Host|PropelObjectCollection $host The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 HostIpQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHost($host, $comparison = null)
    {
        if ($host instanceof Host) {
            return $this
                ->addUsingAlias(HostIpPeer::HOST_ID, $host->getId(), $comparison);
        } elseif ($host instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(HostIpPeer::HOST_ID, $host->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByHost() only accepts arguments of type Host or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Host relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return HostIpQuery The current query, for fluid interface
     */
    public function joinHost($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Host');

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
            $this->addJoinObject($join, 'Host');
        }

        return $this;
    }

    /**
     * Use the Host relation Host object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LF14\SysMgmtBundle\Model\HostQuery A secondary query class using the current class as primary query
     */
    public function useHostQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinHost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Host', '\LF14\SysMgmtBundle\Model\HostQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   HostIp $hostIp Object to remove from the list of results
     *
     * @return HostIpQuery The current query, for fluid interface
     */
    public function prune($hostIp = null)
    {
        if ($hostIp) {
            $this->addUsingAlias(HostIpPeer::ID, $hostIp->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
