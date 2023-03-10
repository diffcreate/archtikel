<?php 

class App{
	protected $controller = Controller;
	protected $method = Method;
	protected $params = [];
	protected $folder;

	public function __construct()
	{
		$url = $this->parseURL();

		if (isset($url[0])) {
			if (file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
				$this->controller = ucfirst($url[0]);
				unset($url[0]);
				require_once '../app/controllers/' . $this->controller . '.php';
			}

			elseif (isset($url[1]) && file_exists('../app/controllers/' .ucfirst($url[0]). '/' . ucfirst($url[1]) . '.php')) {
				$this->folder = ucfirst($url[0]);
				$this->controller = ucfirst($url[1]);
				unset($url[0]);
				unset($url[1]);
				require_once '../app/controllers/' . $this->folder .  '/' . $this->controller . '.php';
			}
			else{
				require_once '../app/controllers/' . ucfirst($this->controller) . '.php';
			}
		}
		else{
			require_once '../app/controllers/' . ucfirst($this->controller) . '.php';
		}




		$this->controller = new $this->controller;

		if (isset($url[1])) {
			if (method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}
		elseif (isset($url[2])) {
			if (method_exists($this->controller, $url[2])) {
				$this->method = $url[2];
				unset($url[2]);
			}
		}

		if (!empty($url)) {
			$this->params = array_values($url);
		}

		call_user_func_array([$this->controller, $this->method], $this->params);




	}

	public function parseURL()
	{
		if (isset($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);

			return $url;
		}
	}







}