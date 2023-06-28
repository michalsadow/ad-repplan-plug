<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataRepplanPlug;

use DateTime;
use Przeslijmi\AgileData\Tools\JsonSettings as Json;
use Przeslijmi\AgileDataRepplanPlug\IndexHandler;
use Przeslijmi\RepaymentPlanner\Schedule;
use Przeslijmi\XlsxPeasant\Items\Font;
use Przeslijmi\XlsxPeasant\Items\Format\DateFormat;
use Przeslijmi\XlsxPeasant\Items\Format\NumFormat;
use Przeslijmi\XlsxPeasant\Xlsx;
use stdClass;

/**
 * Manager for handling repayment planners.
 */
class Plan
{

    /**
     * Unique id of plan - 12 lenght.
     *
     * @var string
     */
    private $id;

    /**
     * Definition of this plan.
     *
     * @var stdClass
     */
    private $def;

    /**
     * Create new plan.
     *
     * @param string|null $id Optional. Unique id of plan. If not given - new is generated - used for creating.
     */
    public function __construct(?string $id = null)
    {

        // Define id.
        if ($id === null) {
            $this->id = $this->calcRandomId();
        } else {
            $this->id = $id;
            $this->setDef(json_decode(file_get_contents($this->getUri())));
        }
    }

    /**
     * Deletes this nip from app.
     *
     * @return void
     */
    public function delete(): void
    {

        // Delete file.
        if (file_exists($this->getUri()) === true) {
            unlink($this->getUri());
        }

        // Call index.
        $index = new IndexHandler();
        $index->update($this->id, null);
    }

    /**
     * Define plan with given info.
     *
     * @param stdClass $def Complete definition of a plan.
     *
     * @return void
     */
    public function setDef(stdClass $def): void
    {

        $this->def = $def;
    }

    /**
     * Deliver info about plan.
     *
     * @return stdClass
     */
    public function getDef(): ?stdClass
    {

        return $this->def;
    }

    /**
     * Saves plan into drive.
     *
     * @return void
     */
    public function save(): void
    {

        // Save.
        file_put_contents($this->getUri(), json_encode($this->def, Json::stdWrite()));

        // Update index.
        $index = new IndexHandler();
        $index->update($this->id, $this->def);
    }

    /**
     * Sets plan in force or not in force.
     *
     * @param boolean $force Set to `true` to enforce, set to `false` to deforce.
     *
     * @return void
     */
    public function setInForce(bool $force): void
    {

        $this->def->inForce = ( ( $force === true ) ? 'yes' : 'no' );
    }

    /**
     * Generates CSV and XLSX file.
     *
     * @return void
     */
    public function generate(): void
    {

        // Generate CSV.
        $schedule = $this->generateCsv();

        // Generate XLSX.
        $this->generateXlsx($schedule);
    }

    /**
     * Generates XLSX file.
     *
     * @param Schedule $schedule Schedule to show in XLSX format.
     *
     * @return void
     */
    private function generateXlsx(Schedule $schedule): void
    {

        // Lvd.
        $xlsx  = new Xlsx();
        $sheet = $xlsx->getBook()->addSheet('Harmonogram');
        $uri   = $this->getDirUri() . $this->id . '.xlsx';
        $rows  = [];

        // Create table of repayments.
        foreach ($schedule->getInstallments()->getAll() as $installment) {

            // Lvd.
            $period = $installment->getPeriod();

            // Add row.
            $rows[] = [
                'Nr.' => ( count($rows) + 1 ),
                'Początek okresu' => $period->getFirstDay()->format('Y-m-d'),
                'Koniec okresu' => $period->getLastDay()->format('Y-m-d'),
                'Odsetki' => $installment->getInterests(),
                'Kapitał' => $installment->getCapital(),
                'Rata łączna' => $installment->getWhole(),
            ];
        }

        // Define design.
        $sheet->setColWidth(1, 10.00);
        $sheet->setColWidth(2, 16.00);
        $sheet->setColWidth(3, 16.00);
        $sheet->setColWidth(4, 13.00);
        $sheet->setColWidth(5, 13.00);
        $sheet->setColWidth(6, 13.00);

        // Define info header.
        $xlsx->useAlign('RM');
        $xlsx->useFont(Font::factory(null, '8 italic'));
        $sheet->getCell(1, 1)->setValue('O ID ' . $this->id . ' wygenerowany ' . date('Y-m-d H:i:s') . '.');
        $sheet->getCell(1, 1)->setMerge(1, 6);

        // Define main header.
        $xlsx->useAlign('CM');
        $xlsx->useFont(Font::factory(null, '16 bold'));
        $sheet->getCell(3, 1)->setValue('Harmonogram spłat pożyczki');
        $sheet->getCell(3, 1)->setMerge(1, 6);
        $sheet->setRowHeight(3, 25.00);
        $xlsx->useFont(null);

        // Define description.
        $xlsx->useAlign('RM');
        $sheet->getCell(5, 2)->setValue('Klient');
        $sheet->getCell(6, 2)->setValue('Numer umowy');
        $sheet->getCell(7, 2)->setValue('Kwota umowy');
        $sheet->getCell(8, 2)->setValue('Bieżąca stopa procentowa');
        $xlsx->useFont(Font::factory(null, 'bold'));
        $xlsx->useAlign('LM');
        $sheet->getCell(5, 3)->setValue($this->def->name);
        $sheet->getCell(6, 3)->setValue($this->def->agreement);
        $xlsx->useAlign('RM');
        $xlsx->useFormat(new NumFormat(2, 0, 'zł'));
        $sheet->getCell(7, 3)->setValue(array_sum(array_column(( $this->def->payments ?? [] ), 'amount')));
        $xlsx->useFormat(new NumFormat(2, 0, '%'));
        $sheet->getCell(8, 3)->setValue($this->getRateAtDate(date('Y-m-d')));
        $xlsx->useAlign(null);
        $xlsx->useFormat(null);
        $xlsx->useFont(null);

        // Add table with rows.
        $table = $sheet->addTable('Raty', 10, 1);
        $table->addColumns([
            'Nr.',
            'Początek okresu',
            'Koniec okresu',
            'Odsetki',
            'Kapitał',
            'Rata łączna',
        ]);
        $table->getColumnByName('Nr.')->setFormat(new NumFormat(0, 0, ''));
        $table->getColumnByName('Początek okresu')->setFormat(new DateFormat());
        $table->getColumnByName('Koniec okresu')->setFormat(new DateFormat());
        $table->getColumnByName('Odsetki')->setFormat(new NumFormat(2, 0, 'zł'));
        $table->getColumnByName('Kapitał')->setFormat(new NumFormat(2, 0, 'zł'));
        $table->getColumnByName('Rata łączna')->setFormat(new NumFormat(2, 0, 'zł'));
        $table->addData($rows);

        // Generate.
        $xlsx->generate($uri, true);
    }

    /**
     * Generates CSV file.
     *
     * @return Schedule
     */
    private function generateCsv(): Schedule
    {

        // Create schedule object and define it.
        $schedule = new Schedule(
            (float) $this->def->payments[0]->amount,
            (float) $this->def->rates[0]->amount,
            new DateTime($this->def->payments[0]->date),
            new DateTime($this->def->lastRep),
            $this->def->repPeriod
        );
        $schedule->setFirstRepaymentDate(new DateTime($this->def->firstRep));

        // Add next rates (if applicable).
        if (count($this->def->rates) > 1) {
            foreach (array_slice($this->def->rates, 1) as $nextRate) {
                $schedule->addRate(new DateTime($nextRate->date), $nextRate->amount);
            }
        }

        // Add next payments (if applicable).
        if (count($this->def->payments) > 1) {
            foreach (array_slice($this->def->payments, 1) as $nextPayment) {
                $schedule->addPayment(new DateTime($nextPayment->date), $nextPayment->amount);
            }
        }

        // Calculations.
        if ($this->def->repStyle === 'annuit') {
            $schedule->setRepaymentsAnnuitStyle();
        } elseif ($this->def->repStyle === 'annuitZero') {
            $schedule->setRepaymentsAnnuitStyle(0);
        } elseif ($this->def->repStyle === 'baloon') {
            $schedule->setRepaymentsBalloonStyle();
        } else {
            $schedule->setRepaymentsLinearStyle();
        }
        $schedule->calc();
        $schedule->toCsvFile($this->getDirUri() . $this->id . '.csv');

        return $schedule;
    }

    /**
     * Get rate for given date.
     *
     * @param string $date For which date.
     *
     * @return float
     */
    private function getRateAtDate(string $date): float
    {

        // Find amount.
        foreach (array_reverse($this->def->rates) as $rate) {
            if ($date >= $rate->date) {
                return $rate->amount;
            }
        }

        // If not found - than this is the first one.
        return $this->def->rates[0]->amount;
    }

    /**
     * Delivers uri where all plans are held.
     *
     * @return string
     */
    private function getDirUri(): string
    {

        // Get from env.
        $dir = $_ENV['PRZESLIJMI_ADREPPLANPLUG_PLANS_DIR_URI'];

        // Create last dir if not exists.
        if (file_exists($dir) === false) {
            mkdir($dir);
        }

        return $dir;
    }

    /**
     * Delivers uri for this plan.
     *
     * @return string
     */
    private function getUri(): string
    {

        return $this->getDirUri() . $this->id . '.json';
    }

    /**
     * Generate random 12-chars length id of this plan.
     *
     * @return string
     */
    private function calcRandomId(): string
    {

        // Lvd.
        $letters = str_split('qazwsxedcrfvtgbyhnujmikolpQAZWSXEDCRFVTGBYHNUJMIKOLP0123456789');
        $result  = '';

        foreach (array_rand($letters, 12) as $chr) {
            $result .= $letters[$chr];
        }

        return $result;
    }
}
