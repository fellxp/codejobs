<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class Gallery_Controller extends ZP_Load
{

	private $effect = false;

	private $pagination = null;

	public function __construct()
	{
		$this->config("gallery");
		$this->Templates = $this->core("Templates");
		$this->Pagination = $this->core("Pagination");
		$this->Gallery_Model = $this->model("Gallery_Model");
		$this->Users_Model = $this->model("Users_Model");
		$this->application = $this->app("gallery");
		$this->Templates->theme(WEB_THEME);
		$this->CSS("style", $this->application);
	}
	
	public function index()
	{
		if (segment(2) === "image" and segment(3) > 0 and segment(4) !== "" and segment(5) === "prev") {
			$this->getPrev();
		} elseif (segment(2) === "image" and segment(3) > 0 and segment(4) !== "" and segment(5) === "next") {
			$this->getNext();
		} elseif (segment(2) === "image" and segment(3) > 0) {
			$this->showImage();
		} elseif (segment(2) === "album" and segment(3) !== "" and segment(4) === "page" and segment(5) > 0) {
			$this->showGallery();
		} elseif (segment(2) === "album" and segment(3) !== "") {
			$this->showGallery();
		} elseif (segment(2) === "albums" and segment(3) === "page" and segment(4) > 0) {
			$this->showGallery();
		} else {
			$this->showGallery();
		}
	}

	private function showGallery()
	{
		$album = segment(3);
		$this->CSS("skin", $this->application);
		$this->js("jquery.jcarousel.min", $this->application);
		$this->js("albums", $this->application);

		if (segment(2) === "page" and segment(3) > 0) {
			$page = segment(3);
		} elseif (segment(4) === "page" and segment(5) > 0) {
			$page = segment(5);
		} else {
			$page = 0;
		}

		$end = MAX_LIMIT;

		if ($page === 0) {
			$start = 0; 
		} else { 
			$start = ($page * $end) - $end;
		}
		
		$limit = $start .", ". $end;

		if ($album === null) {
			$data = $this->Gallery_Model->getByAlbum(false, $limit);
			$count = $this->Gallery_Model->getCount();
			$URL = path("gallery/page");
		} elseif ($album !== "") {
			$data = $this->Gallery_Model->getByAlbum($album, $limit);
			$count = $this->Gallery_Model->getCount($album);

			$URL = path("gallery/album/". $album ."/page/");
		}

		if ($count > $end) {
			 $pagination = paginate($count, MAX_LIMIT, $start, $URL);
		}

		if (!$data) {
			redirect(WEB_BASE);
		} else {
			if (isset($pagination)) {
				$vars["pagination"] = $pagination;
			}

			$vars["count"] = $count;
			$vars["pictures"] = $data;

			if (!$this->effect) {
				$albums = $this->Gallery_Model->getAlbums();
				$vars["albums"] = $albums;
				$vars["view"][0] = $this->view("gallery", $this->application, true);
				$vars["view"][1] = $this->view("albums", $this->application, true);
			} else {
				$vars["view"] = $this->view("gallery", $this->application, true);
			}

			$this->template("content", $vars);
		}
	}

	public function showImage()
	{
		$data = $this->Gallery_Model->getByID(segment(3), true);

		if (!$data) {
			redirect(path("gallery");
		}

		if ($data["Album"] !== "None") {
			$count = $this->Gallery_Model->getCount($data["Album_Nice"]);
		} else {
			$count = $this->Gallery_Model->getCount();
		}
					

		$vars["count"] = $count;
		$vars["picture"] = $data;

		if (_webGalleryComments) {
			$vars["view"][0] = $this->view("image", $this->application, true);
			$vars["view"][1] = $this->view("comments", $this->application, true);
		} else {
			$vars["view"] = $this->view("image", $this->application, true);
		}

		$this->template("content", $vars);
	}
	
	public function getNext() { 
		if (segment(4) !== "none") {
			$prev = $this->Gallery_Model->getPrev(filter(segment(3)), segment(4));
			$last = $this->Gallery_Model->getLast(segment(4));
		} else {
			$prev = $this->Gallery_Model->getPrev(filter(segment(3)));
			$last = $this->Gallery_Model->getLast();
		}
		
		if ($prev !== false) {

			if ($prev["Album"] !== "None") {
				$count = $this->Gallery_Model->getCount($prev["Album_Nice"]);
			} else {
				$count = $this->Gallery_Model->getCount();
			}
			
			$vars["count"] = $count;
			$vars["picture"] = $prev;

			if (_webGalleryComments) {
				$vars["view"][0] = $this->view("image", $this->application, true);
				$vars["view"][1] = $this->view("comments", $this->application, true);
			} else {
				$vars["view"] = $this->view("image", $this->application, true);
			}

			$this->template("content", $vars);
		} else {
			if (!$last) {
				redirect(path("gallery");
			}

			if ($prev["Album"] !== "None") {
				$count = $this->Gallery_Model->getCount($last["Album_Nice"]);
			} else {
				$count = $this->Gallery_Model->getCount();
			}

			$vars["count"] = $count;
			$vars["picture"] = $last;

			if (_webGalleryComments) {
				$vars["view"][0] = $this->view("image", $this->application, true);
				$vars["view"][1] = $this->view("comments", $this->application, true);
			} else {
				$vars["view"] = $this->view("image", $this->application, true);
			}

			$this->template("content", $vars);
		}
	}

	public function getPrev()
	{
		if (segment(4) !== "none") {
			$next = $this->Gallery_Model->getNext(filter(segment(3)), segment(4));
			$first = $this->Gallery_Model->getFirst(segment(4));
		} else {
			$next = $this->Gallery_Model->getNext(filter(segment(3)));
			$first = $this->Gallery_Model->getFirst();
		}

		if ($next) {
			if ($this->next["Album"] !== "None") {
				$count = $this->Gallery_Model->getCount($this->next["Album_Nice"]);
			} else {
				$count = $this->Gallery_Model->getCount();
			}

			$vars["count"] = $count;
			$vars["picture"] = $this->next;

			if (_webGalleryComments) {
				$vars["view"][0] = $this->view("image", $this->application, true);
				$vars["view"][1] = $this->view("comments", $this->application, true);
			} else {
				$vars["view"] = $this->view("image", $this->application, true);
			}

			$this->template("content", $vars);
		} else {
			if (!$first) {
				redirect(path("gallery");
			}

			if ($first["Album"] !== "None") {
				$count = $this->Gallery_Model->getCount($first["Album_Nice"]);
			} else {
				$count = $this->Gallery_Model->getCount();
			}

			$vars["count"] = $count;
			$vars["picture"] = $first;

			if (_webGalleryComments) {
				$vars["view"][0] = $this->view("image", $this->application, true);
				$vars["view"][1] = $this->view("comments", $this->application, true);
			} else {
				$vars["view"] = $this->view("image", $this->application, true);
			}

			$this->template("content", $vars);
		}
	}
}