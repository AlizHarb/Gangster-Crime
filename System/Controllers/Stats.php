<?php


class Stats extends Controller
{

    public function __construct()
    {
        parent::__construct(true, false, false);
    }

    public function index()
    {
        $stats = Database::getInstance()->countAll("gangstersStats", array(
            'GS_cash' => 'cash',
            'GS_bank' => 'bank',
            'GS_bullets' => 'bullets',
            'GS_prisonSuccess' => 'prisonSuccess',
            'GS_prisonFailed' => 'prisonFail',
            'GS_autostolen' => 'stolen',
        ));
        $gangsters = Database::getInstance()->get("gangsters", array(
            array('id', '>', 0)
        ));
        $gangsters = $gangsters->count();

        $this->view('stats', array(
            'stats'     => $stats,
            'gangsters' => $gangsters
        ));
    }
}