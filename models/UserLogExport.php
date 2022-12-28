<?php namespace Axen\Sso\Models;


use Axen\Sso\Models\Log;
use Backend\Models\ExportModel;
use Illuminate\Support\Facades\Log as PSRLOG;

class UserLogExport extends ExportModel
{
    public $table = 'axen_sso_logs';
    protected $fillable = ['export_from','export_to','action','profission','specs'];
    public function exportData($columns, $sessionKey = null)
    {
        $query = Log::with('user');
        if ($this->action != null) {
            $query = $query->where('action_type',$this->action);
        }
        if ($this->export_from != null) {
            $query = $query->where('action_time','>=',$this->export_from);
        }
        if ($this->export_to != null) {
            $query = $query->where('action_time','<=',$this->export_to);
        }
      
        $query = $query->get();
        if ($this->profission != null) {
            $query = $query->filter(function($item) {  
                return $item->user->profession == $this->profission;
            });
        }
        if ($this->specs != null) {
            $query = $query->filter(function($item) {  
                return $item->user->specialization == $this->specs;
            });
        }
        
        return $query->toArray();
    }
   



    public function getProfissionOptions() {
            $file = __DIR__ . '/../data/profissions.json';
            $profs = json_decode(file_get_contents($file), true);
            $items = [
                '' => 'ANY'
            ];
            foreach($profs as $key=>$prof) {
                $items[$prof['name']] = $prof['name'];
            }
            return $items;
    }
    public function getSpecsOptions() {
 
        $file = __DIR__ . '/../data/profissions.json';
        $profs = json_decode(file_get_contents($file), true);
        $specs = [];
        $items = [
            '' => 'ANY'
        ];
        foreach($profs as $prof) {
            if ($prof['name']== $this->profission) {
                foreach ($prof['specializations'] as $key=>$spec) {
                    $items[$spec['name']] = $spec['name'];
                }
            }
        }   
      
        
        return $items;
    }
    
}