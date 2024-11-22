<?php   namespace App\Repositories\Document;

use App\Classes\ToolsClass;
use App\Interfaces\Document\RecordRepositoryInterface;
use App\Models\Document\RecordModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RecordRepository implements RecordRepositoryInterface 
{
    private $tool;

    public function __construct(ToolsClass $Tools)
    {
        $this->tool = $Tools;
    }


} // class