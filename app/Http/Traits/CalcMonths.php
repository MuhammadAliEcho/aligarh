<?php
namespace APP\Http\Traits;
use Carbon\Carbon;

trait CalcMonths {

	protected $months = [];

	protected $start, $end;

	public function getMonths(){
		return $this->months;
	}

	public function getMonthsFromDates($start, $end){

		$this->start = $start;
		$this->end = $end;

		return $this->setDate();
	}

	public function getMonthsFromSession($session){

		$this->start = $session->getOriginal('start');
		$this->end = $session->getOriginal('end');
		return $this->setDate();
	}

	protected function setDate(){

		$date = $this->start;
		while ($date <= $this->end) {

			$this->months[Carbon::parse($date)->format('M')] = Carbon::parse($date)->format('Y-m-d');
			// $this->months[Carbon::createFromFormat('Y-m-d', $date)->format('M')] = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');


			$date = Carbon::parse($date)->addMonth()->format('Y-m-d');
		}

		return $this->months;
	}

}