<?php

namespace AWX;

use AWX\AWXException;

/**
 *
 */
class AWXConnector
{

    /**
     * @var string
     * Token generate on AWX
     * http://sts306wk12:9292/#/users/82/tokens?user_search=page_size:20;order_by:username&token_search=page_size:10;order_by:application__name
     */
    protected string $_oauth2_token = '';

    /**
     * Using sts306 proxy on dev environment or Azure
     * @var string
     */
    protected string $_ansible_base_uri = '';

    public $_stdout;

    /**
     * API V2 PING URI
     */
    const  URI_PING = 'ping/';

    /**
     * API V2 PROJECTS URI
     */
    const  URI_PROJECTS = 'projects/';

    /**
     * API V2 CREDENTIALS URI
     */
    const  URI_CREDENTIALS = 'credentials/';

    /**
     * API V2 CREDENTIALS TYPE URI
     */
    const  URI_CREDENTIALS_TYPE = 'credential_types/';

    /**
     * API V2 INVENTORY URI
     */
    const  URI_INVENTORY = 'inventories/';

    /**
     * API V2 HOSTS URI
     */
    const  URI_HOSTS = 'hosts/';

    /**
     * API V2 JOB TEMPLATES URI
     */
    const  URI_JOB_TEMPLATES = 'job_templates/';

    /**
     * API V2 JOBS URI
     */
    const  URI_JOBS = 'jobs/';

    /**
     * API V2 JOB EVENTS URI
     */
    const  URI_JOB_EVENTS = 'job_events/';

    /**
     * API V2 AD HOCK COMMANDS URI
     */
    const  URI_AD_HOCK_COMMANDS = 'ad_hoc_commands/';

    /**
     * @const FOR _STDAPICALL METHOD
     */
    const API_METHOD_GET = 'GET';

    /**
     * @const FOR _STDAPICALL METHOD
     */
    const API_METHOD_POST = 'POST';

    /**
     * @const FOR _STDAPICALL METHOD
     */
    const API_METHOD_PUT = 'PUT';

    /** @const FOR _STDAPICALL METHOD
     *
     */
    const API_METHOD_OPTIONS = 'OPTIONS';

    /**
     * @const FOR _STDAPICALL METHOD
     */
    const API_METHOD_DELETE = 'DELETE';

    /**
     * AnsibleAPI constructor.
     * @throws AWXException
     */
    public function __construct($_oauth2_token,$uri)
    {

        $this->_oauth2_token = $_oauth2_token;
        $this->_ansible_base_uri = $uri;

        $ping_test = json_decode($this->_STDAPICALL($this->_ansible_base_uri.$this::URI_PING,self::API_METHOD_GET),true);

        if(!isset($ping_test['version']))
            throw new AWXException('Unable to connect with ansible');


    }

    /**
     * @param $URI
     * @param $METHOD
     * @param string $DATA_JSON
     * @return bool|string
     */
    private function _STDAPICALL($URI, $METHOD, $DATA_JSON = '')
    {

        $curl_resource = curl_init($URI);

        curl_setopt_array(
            $curl_resource,
            [
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_PROXY => '',
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'User-Agent: HttpClient',
                    'Connection: Keep-Alive',
                    "Authorization: {$this->_oauth2_token}",
                ],
            ]
        );

        if($METHOD != self::API_METHOD_GET)
            curl_setopt($curl_resource,CURLOPT_CUSTOMREQUEST,$METHOD);

        if($DATA_JSON != '')
            curl_setopt($curl_resource,CURLOPT_POSTFIELDS,$DATA_JSON);

        return curl_exec($curl_resource);

    }

    /**
     * @return mixed
     */
    public function listProjects()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_PROJECTS, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listCredentials()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_CREDENTIALS, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listCredentialsType()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_CREDENTIALS_TYPE, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listInventories()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_INVENTORY, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listHosts()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_HOSTS, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listJobTemplates()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_JOB_TEMPLATES, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listJobs()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_JOBS, self::API_METHOD_GET), true);

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function listJobsById(int $id)
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_JOBS . "?id=$id", self::API_METHOD_GET), true);

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function listJobStdout(int $id)
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . "jobs/$id/stdout/?format=json", self::API_METHOD_GET), true);

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function listJobByID(int $id)
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . 'jobs/?id=' . $id, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function listJobsEvents()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_JOB_EVENTS, self::API_METHOD_GET), true);

    }

    /**
     * @return mixed
     */
    public function adHockCommands()
    {

        return json_decode($this->_STDAPICALL($this->_ansible_base_uri . $this::URI_AD_HOCK_COMMANDS, self::API_METHOD_GET), true);

    }

    /**
     * @param int $job_template_id
     * @param string $json_data
     * @return array
     */
    public function launchJob(int $job_template_id, string $json_data): array
    {


        return json_decode($this->_STDAPICALL(
            "{$this->_ansible_base_uri}job_templates/$job_template_id/launch/",
            self::API_METHOD_POST,
            $json_data
        ),
            true
        );

    }


}