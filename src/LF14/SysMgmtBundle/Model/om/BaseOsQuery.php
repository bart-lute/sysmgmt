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
use LF14\SysMgmtBundle\Model\Os;
use LF14\SysMgmtBundle\Model\OsPeer;
use LF14\SysMgmtBundle\Model\OsQuery;

/**
 * @method OsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method OsQuery orderByPlatform($order = Criteria::ASC) Order by the platform column
 * @method OsQuery orderByDistro($order = Criteria::ASC) Order by the distro column
 * @method OsQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method OsQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method OsQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method OsQuery groupById() Group by the id column
 * @method OsQuery groupByPlatform() Group by the platform column
 * @method OsQuery groupByDistro() Group by the distro column
 * @method OsQuery groupByVersion() Group by the version column
 * @method OsQuery groupByCreatedAt() Group by the created_at column
 * @method OsQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method OsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method OsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method OsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method OsQuery leftJoinHost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Host relation
 * @method OsQuery rightJoinHost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Host relation
 * @method OsQuery innerJoinHost($relationAlias = null) Adds a INNER JOIN clause to the query using the Host relation
 *
 * @method Os findOne(PropelPDO $con = null) Return the first Os matching the query
 * @method Os findOneOrCreate(PropelPDO $con = null) Return the first Os matching the query, or a new Os object populated from the query conditions when no match is found
 *
 * @method Os findOneByPlatform(string $platform) Return the first Os filtered by the platform column
 * @method Os findOneByDistro(string $distro) Return the first Os filtered by the distro column
 * @method Os findOneByVersion(string $version) Return the first Os filtered by the version column
 * @method Os findOneByCreatedAt(string $created_at) Return the first Os filtered by the created_at column
 * @method Os findOneByUpdatedAt(string $updated_at) Return the first Os filtered by the updated_at column
 *
 * @method array findById(int $id) Return Os objects filtered by the id column
 * @method array findByPlatform(string $platform) Return Os objects filtered by the platform column
 * @method array findByDistro(string $distro) Return Os objects filtered by the distro column
 * @method array findByVersion(string $version) Return Os objects filtered by the version column
 * @method array findByCreatedAt(string $created_at) Return Os objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Os objects filtered by the updated_at column
 */
abstract class BaseOsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseOsQuery object.
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
            $modelName = 'LF14\\SysMgmtBundle\\Model\\Os';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new OsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   OsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return OsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof OsQuery) {
            return $criteria;
        }
        $query = new OsQuery(null, null, $modelAlias);

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
     * @return   Os|Os[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = OsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(OsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Os A model object, or null if the key is not found
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
     * @return                 Os A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `platform`, `distro`, `version`, `created_at`, `updated_at` FROM `os` WHERE `id` = :p0';
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
            $obj = new Os();
            $obj->hydrate($row);
            OsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Os|Os[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Os[]|mixed the list of results, formatted by the current formatter
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
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OsPeer::ID, $keys, Criteria::IN);
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
     * @return OsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(OsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(OsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OsPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the platform column
     *
     * Example usage:
     * <code>
     * $query->filterByPlatform('fooValue');   // WHERE platform = 'fooValue'
     * $query->filterByPlatform('%fooValue%'); // WHERE platform LIKE '%fooValue%'
     * </code>
     *
     * @param     string $platform The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByPlatform($platform = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($platform)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $platform)) {
                $platform = str_replace('*', '%', $platform);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OsPeer::PLATFORM, $platform, $comparison);
    }

    /**
     * Filter the query on the distro column
     *
     * Example usage:
     * <code>
     * $query->filterByDistro('fooValue');   // WHERE distro = 'fooValue'
     * $query->filterByDistro('%fooValue%'); // WHERE distro LIKE '%fooValue%'
     * </code>
     *
     * @param     string $distro The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByDistro($distro = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($distro)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $distro)) {
                $distro = str_replace('*', '%', $distro);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OsPeer::DISTRO, $distro, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE version = 'fooValue'
     * $query->filterByVersion('%fooValue%'); // WHERE version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $version The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $version)) {
                $version = str_replace('*', '%', $version);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(OsPeer::VERSION, $version, $comparison);
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
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(OsPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(OsPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OsPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return OsQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(OsPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(OsPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OsPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Host object
     *
     * @param   Host|PropelObjectCollection $host  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 OsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHost($host, $comparison = null)
    {
        if ($host instanceof Host) {
            return $this
                ->addUsingAlias(OsPeer::ID, $host->getOsId(), $comparison);
        } elseif ($host instanceof PropelObjectCollection) {
            return $this
                ->useHostQuery()
                ->filterByPrimaryKeys($host->getPrimaryKeys())
                ->endUse();
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
     * @return OsQuery The current query, for fluid interface
     */
    public function joinHost($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useHostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinHost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Host', '\LF14\SysMgmtBundle\Model\HostQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Os $os Object to remove from the list of results
     *
     * @return OsQuery The current query, for fluid interface
     */
    public function prune($os = null)
    {
        if ($os) {
            $this->addUsingAlias(OsPeer::ID, $os->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
