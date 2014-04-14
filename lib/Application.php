<?php

final class App
{
    /**
     * Application Constants
     */
    const VIEW_EXT = '.php';

    /**
     * Application views
     */
    const VIEW_INDEX = 'index';
    const VIEW_JSON_MEMBERS = 'json_members';

    /**
     * @var static $App
     */
    private static $App;

    /**
     * Application Options
     *
     * @var array $options
     */
    private static $options = array
    (
        'db_connection' => null
    );

    /**
     * Application constructor is private in order to use singleton
     */
    private function __construct(){}

    /**
     * Register New Instance of App
     *
     * @param array $options
     * @return App
     */
    public static function register($options = array())
    {
        //-- merge app options
        static::$options = array_merge(static::$options, $options);

        //-- register new instance
        if (is_null(static::$App)) static::$App = new static();

        //-- return instance
        return static::$App;
    }

    /**
     * Database Manager
     *
     * @return \Doctrine\DBAL\Connection
     */
    protected function db()
    {
        return \Doctrine\DBAL\DriverManager::getConnection
        (
            static::$options['db_connection'],
            new \Doctrine\DBAL\Configuration()
        );
    }

    /**
     * Include View
     *
     * @param $view
     * @param array $arguments
     * @throws ErrorException
     */
    protected function view($view, $arguments = array())
    {
        foreach ($arguments as $key => $argument)
        {
            //-- if key is not string continue
            if (is_int($key)) continue;

            //-- set view variable name
            $name = (string) $key;

            //-- assign value to variable
            ${$name} = $argument;
        }

        //-- path to view
        $view_path = 'views'.DIRECTORY_SEPARATOR.$view.static::VIEW_EXT;

        //-- if view file is not found throw exception
        if (!file($view_path))
        {
            throw new ErrorException($view.' view not found!');
        }

        //-- include view
        include_once($view_path);
    }

    /**
     * Route and Run the Application
     */
    public function route($view = self::VIEW_INDEX)
    {
        try
        {
            //-- route views
            switch($view)
            {
                //-- route members json
                case self::VIEW_INDEX:
                    $this->homepage();
                    break;

                //-- route homepage
                case self::VIEW_JSON_MEMBERS:
                    $this->jsonMembers();
                    break;

                //-- route default
                default:
                    //-- show 404
                    $this->view('404');
                    break;
            }
        }
        catch(\Exception $ex)
        {
            //-- include error view
            $this->view('error');
        }
    }

    /**
     * Get All Team Members
     *
     * @return array
     * @throws Exception
     */
    protected function getTeamMembers()
    {
        try
        {
            //-- sql with team members
            $sql = 'SELECT id, first_name, last_name FROM team_members';

            //-- return array with members
            return $this->db()->fetchAll($sql);
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }

    /**
     * Get Team Member Data and devices
     *
     * @param int $team_member_id
     * @return array
     */
    private function getTeamMemberDevices($team_member_id)
    {
        //-- build sql to get the team member
        $sql = "SELECT id, first_name, last_name, email from team_members WHERE id = ?";

        //-- exec query
        $team_member = $this->db()->fetchAssoc($sql, array($team_member_id), array(\PDO::PARAM_INT));

        //-- build sql
        $sql = "SELECT
                  d.id,
                  b.name as brand,
                  d.model,
                  d.custody_from,
                  d.custody_till
                FROM team_member_devices AS tmd
                INNER JOIN devices as d ON (tmd.device_id = d.id)
                INNER JOIN brands as b ON (d.brand_id = b.id)
                WHERE tmd.team_member_id = ?";

        //-- exec query
        $devices = $this->db()->fetchAll($sql, array($team_member_id), array(\PDO::PARAM_INT));

        //-- return array with data
        return array('team_member' => $team_member, 'devices' => $devices);
    }

    /**
     * Homepage view
     */
    protected function homepage()
    {
        //-- get members
        $members = $this->getTeamMembers();

        //-- include view
        $this->view('index', array('members' => $members));
    }

    /**
     * Json Members
     */
    protected function jsonMembers()
    {
        //-- get user_id
        $team_member_id = (isset($_POST['user_id']) ? $_POST['user_id'] : null);

        if ($team_member_id)
        {
            //-- get data
            $data = $this->getTeamMemberDevices($team_member_id);

            //-- include view
            $this->view('json_members', array
            (
                'status' => true,
                'errors' => array(),
                'data'   => $data
            ));
        }
    }
}