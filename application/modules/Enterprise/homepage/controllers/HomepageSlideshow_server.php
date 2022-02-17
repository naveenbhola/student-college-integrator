<?php
/**
 * This class provides web services to student dashboeard client
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class HomepageSlideshow_server extends MX_Controller
{
	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function index()
	{

		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('userconfig');
		$config['functions']['addContentToCarouselWidget'] = array('function' => 'HomepageSlideshow_server.addContentToCarouselWidget');
		$config['functions']['renderCarouselDeatils'] = array('function' => 'HomepageSlideshow_server.renderCarouselDeatils');
		$config['functions']['updateCarouselDeatils'] = array('function' => 'HomepageSlideshow_server.updateCarouselDeatils');
		$config['functions']['deleteCarousel'] = array('function' => 'HomepageSlideshow_server.deleteCarousel');
		$config['functions']['reorderCarousel'] = array('function' => 'HomepageSlideshow_server.reorderCarousel');
		$config['functions']['getDataForHomepageCafeWidget'] = array('function' => 'HomepageSlideshow_server.getDataForHomepageCafeWidget');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function addContentToCarouselWidget($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		error_log('aditya'.print_r($parameters,true));
		$carousel_title = $parameters['0'];
		$carousel__destination_url = $parameters['1'];
		$carousel_photo_url = $parameters['2'];
		$carousel_description = $parameters['3'];
		$carousel_open_new_window = $parameters['4'];
		$result_array = array();
		$this->load->model('homepageslideshowmodel');
		$model_obj = new homepageslideshowmodel();
		$rows = $model_obj->addContentToCarouselWidget($carousel_title,$carousel__destination_url,$carousel_photo_url,$carousel_description,$carousel_open_new_window);
		// send the response back
		return $this->xmlrpc->send_response(json_encode($rows));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function renderCarouselDeatils($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		error_log('aditya'.print_r($parameters,true));
		$carousel_id = $parameters['0'];
		$this->load->model('homepageslideshowmodel');
		$model_obj = new homepageslideshowmodel();
		$rows = $model_obj->renderCarouselDeatils($carousel_id);
		// send the response back
		return $this->xmlrpc->send_response(json_encode($rows));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function updateCarouselDeatils($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$parameters = json_decode($parameters[0],true);
		error_log('aditya'.print_r($parameters,true));
		$this->load->model('homepageslideshowmodel');
		$model_obj = new homepageslideshowmodel();
		$rows = $model_obj->updateCarouselDeatils($parameters);
		return $this->xmlrpc->send_response(json_encode($rows));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function deleteCarousel($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$carousel_id = json_decode($parameters[0],true);
                $carousel_order = json_decode($parameters[1],true);
		error_log('aditya'.print_r($parameters,true));
		$this->load->model('homepageslideshowmodel');
		$model_obj = new homepageslideshowmodel();
		$rows = $model_obj->deleteCarousel($carousel_id,$carousel_order);
		return $this->xmlrpc->send_response(json_encode($rows));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function reorderCarousel($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		$parameters = json_decode($parameters[0],true);
		error_log('aditya'.print_r($parameters,true));
		$this->load->model('homepageslideshowmodel');
		$model_obj = new homepageslideshowmodel();
		$rows = $model_obj->reorderCarousel($parameters);
		return $this->xmlrpc->send_response(json_encode($rows));
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getDataForHomepageCafeWidget($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		$parameters = json_decode($parameters[0],true);
		error_log('aditya'.print_r($parameters,true));
		$this->load->model('homepageslideshowmodel');
		$model_obj = new homepageslideshowmodel();
		$rows = $model_obj->getDataForHomepageCafeWidget($parameters);
		return $this->xmlrpc->send_response(json_encode($rows));
	}
}
?>
