<?php


class Stats extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, false, false);
    }

    public function index()
    {
        $stats = $this->_db->countAll("gangstersStats", array(
            'GS_cash' => 'cash',
            'GS_bank' => 'bank',
            'GS_bullets' => 'bullets',
            'GS_prisonSuccess' => 'prisonSuccess',
            'GS_prisonFailed' => 'prisonFail',
            'GS_autostolen' => 'stolen',
        ));
        $gangsters = $this->_db->get("gangsters", array(
            array('id', '>', 0)
        ));
        $gangsters = $gangsters->count();

        $this->view('stats', array(
            'stats'     => $stats,
            'gangsters' => $gangsters
        ));
    }
}