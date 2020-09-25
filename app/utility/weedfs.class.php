<?php
class utility_weedfs{
	/** @var Curl transport */
	protected $transport;
	private $masterHost;
	/**
	 * Instantiate a new client.
	 *
	 */
	public function __construct($masterHost){
		// Load the configuration for this group
		$this->masterHost= $masterHost;
		$this->transport = new WeedFSCurl();
	}
	/**
	 *
	 * Get a fid and a volume server url
	 *
	 * for replication options see:
	 * http://code.google.com/p/weed-fs/#Rack-Aware_and_Data_Center-Aware_Replication
	 * 
	 * @param number $count
	 * @param string $replication
	 * @return mixed response from curl
	 */
	public function assign($count = 1, $replication = null)
	{
		$assignUrl = $this->masterHost . '/dir/assign';
		$assignUrl .= '?count=' . (int)($count);

		if($replication !== null) {
			$assignUrl .= '&replication=' . $replication;
		}

		$response = $assignUrl = $this->transport->get($assignUrl);

		$this->transport->close();
		// {"count":1,"fid":"3,01637037d6","url":"127.0.0.1:8080","publicUrl":"localhost:8080"}
		return $response;
	}
	/**
	 *
	 * Delete a file by fid on specified volume server
	 *
	 * @param string $storageVolumeAddress
	 * @param string $fid file id
	 * @return mixed
	 */
	public function delete($volumeServerAddress, $fid)
	{
		$deleteUrl = $volumeServerAddress . '/' . $fid;
		// TODO check for http://

		$response = $this->transport->custom($deleteUrl, 'DELETE');

		$this->transport->close();
		return $response;
	}
	/**
	 *
	 * Lookup locations for specified volume by id
	 *
	 * @param number $volumeId
	 * @return mixed
	 */
	public function lookup($volumeId)
	{
		$lookupUrl = $this->masterHost . '/dir/lookup';
		$lookupUrl .= '?volumeId=' . $volumeId;

		$response = $this->transport->get($lookupUrl);

		$this->transport->close();
		// {"locations":[{"publicUrl":"localhost:8080","url":"localhost:8080"}]}
		return $response;
	}

	/**
	 *
	 * This will assign $count volumes with $replication replication.
	 *
	 * for replication options see:
	 * http://code.google.com/p/weed-fs/#Rack-Aware_and_Data_Center-Aware_Replication
	 *
	 * @param number $count number of volumes
	 * @param string $replication something like 001
	 */
	public function grow($count, $replication)
	{
		$growUrl = $this->masterHost . '/vol/grow';
		$growUrl .= '?count=' . $count;
		$growUrl .= '&replication=' . $replication;

		$response = $this->transport->get($growUrl);

		$this->transport->close();
		return $response;
	}

	/**
	 *
	 * Retrieve a file from a specific volume server by fid
	 *
	 * @param string $volumeServerAddress
	 * @param string $fid
	 * @return mixed
	 */
	public function retrieve($volumeServerAddress, $fid)
	{
		$retrieveUrl = $volumeServerAddress . '/' . $fid;
		// TODO check for http://

		$response = $this->transport->get($retrieveUrl);

		$this->transport->close();
		return $response;
	}
	/**
	 *
	 * Get information about volume's free space
	 *
	 */
	public function status()
	{
		$statusAddress = $this->masterHost . '/dir/status';

		$response = $this->transport->get($statusAddress);

		$this->transport->close();
		return $response;
	}

	/**
	 * 
	 * Is in source, haven't tested
	 * 
	 * @return mixed
	 */
	public function volumeStatus()
	{
		$statusAddress = $this->masterHost . '/vol/status';

		$response = $this->transport->get($statusAddress);

		$this->transport->close();
		return $response;
	}

	/**
	 * 
	 * Is in source, need to test
	 * 
	 * @param string $volumeServerAddress
	 */
	public function volumeServerStatus($volumeServerAddress)
	{
		$statusAddress = $volumeServerAddress . '/status';

		$response = $this->transport->get($statusAddress);

		$this->transport->close();

		return $response;
	}
	/**
	 * 
	 * Store multiple files at once, assuming you have assigned the same number of count for fid
	 * as you have number of files.
	 * 
	 * @param string $volumeServerAddress
	 * @param string $fid base fid for all files
	 * @param array $files
	 * @return mixede
	 */
	public function storeMultiple($volumeServerAddress, $fid, array $files)
	{
		$count = count($files);

		$storeUrl = $volumeServerAddress . '/' . $fid;
		// TODO check for http://
		$response = array();
		for($i = 1; $i <= $count; $i++) {
			$parameters = array('file'=>$files[$i-1]);

			$response[] = $this->transport->post($storeUrl, $parameters);

			$storeUrl = $volumeServerAddress . '/' . $fid . '_' . $i;
		}

		$this->transport->close();
		return $response;
	}
	/**
	 * 
	 * Store a single file on volume server. Use assign first to get the volume server
	 * and fid
	 * 
	 * @param string $volumeServerAddress
	 * @param string $fid
	 * @param unknown $file
	 * @return mixed
	 */
	public function store($volumeServerAddress, $fid, $file)
	{
		$storeUrl = $volumeServerAddress . '/' . $fid;

		$parameters = array('file'=>$file);

		$response = $this->transport->post($storeUrl, $parameters);

		$this->transport->close();
		return $response;
	}
	public function submit($file)
	{
		$submitUrl = $this->masterHost . '/submit';

		$parameters = array('file'=>$file);

		$response = $this->transport->post($submitUrl, $parameters);

		$this->transport->close();
		return $response;
	}
}
class WeedFSCurl{
	protected $curl = 0;
	public function get($url)
	{
		return $this->doRequest($url, array(
					CURLOPT_AUTOREFERER => 1,
					CURLOPT_FOLLOWLOCATION => 1,
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_HEADER => 0,
					));
	}
	public function post($url, $data)
	{
		return $this->doRequest($url, array(
					CURLOPT_AUTOREFERER => 1,
					CURLOPT_FOLLOWLOCATION => 1,
					CURLOPT_RETURNTRANSFER=>1,
					CURLOPT_HEADER => 0,
					CURLOPT_POSTFIELDS => $data,
					));
	}
	public function custom($url, $command)
	{
		return $this->doRequest($url, array(
					CURLOPT_AUTOREFERER => 1,
					CURLOPT_FOLLOWLOCATION => 1,
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_CUSTOMREQUEST => $command,
					));
	}
	private function doRequest($url, $options)
	{
		$this->initCurl();
		$this->setCurlOptions($options);
		$this->setCurlTargetUrl($url);
		return $this->execCurl();
	}
	private function setCurlTargetUrl($url)
	{
		curl_setopt($this->curl, CURLOPT_URL, $url);
	}
	private function setCurlOptions(array $options)
	{
		foreach($options as $option=>$value) {
			curl_setopt($this->curl, $option, $value);
		}
	}
	private function initCurl()
	{
		if($this->curl === 0) {
			$this->curl = curl_init();
		}
	}
	private function execCurl()
	{
		return curl_exec($this->curl);
	}
	public function close()
	{
		if($this->curl !== 0) {
			curl_close($this->curl);
			$this->curl = 0;
		}
	}
}
