<?php namespace App\Http\Controller\Document;

use App\Classes\ToolsClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\Document\RecordRepositoryInterface;
use Illuminate\View\View;

class RecordModelController extends Controller
{
    protected $recordRepo;
    private $tool;
    protected $set;

    public function __construct(RecordRepositoryInterface $recordRepository, ToolsClass $Tools) 
    {
        $this->recordRepo = $recordRepository;
        $this->tool = $Tools;
        $this->set = $this->tool->setSettings('record');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        return view('document.record.index');
    }  // index Method   
    
} // Class
