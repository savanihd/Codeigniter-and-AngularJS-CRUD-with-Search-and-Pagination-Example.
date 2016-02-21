<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		$this->load->database();

		if(!empty($this->input->get("search"))){
			$this->db->like('title', $this->input->get("search"));
			$this->db->or_like('description', $this->input->get("search")); 
		}

		$this->db->limit(5, ($this->input->get("page",1) - 1) * 5);
		$query = $this->db->get("items");

		$data['data'] = $query->result();
		$data['total'] = $this->db->count_all("items");

		echo json_encode($data);
	}

	public function store()
    {
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);
    	$insert = $this->input->post();
		$this->db->insert('items', $insert);

		$id = $this->db->insert_id();
		$q = $this->db->get_where('items', array('id' => $id));
		echo json_encode($q->row());
    }

    public function edit($id)
    {
    	$this->load->database();

		$q = $this->db->get_where('items', array('id' => $id));
		echo json_encode($q->row());
    }

    public function update($id)
    {
    	$this->load->database();
    	$_POST = json_decode(file_get_contents('php://input'), true);

    	$insert = $this->input->post();
    	$this->db->where('id', $id);
    	$this->db->update('items', $insert);

        $q = $this->db->get_where('items', array('id' => $id));
		echo json_encode($q->row());
    }

    public function delete($id)
    {
    	$this->load->database();
        $this->db->where('id', $id);
		$this->db->delete('items');
		echo json_encode(['success'=>true]);
    }
}
