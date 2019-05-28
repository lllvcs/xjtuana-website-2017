<?php

namespace App\Repositories;

use App\Models\Sms;
use Meteorlxy\Laravel\Repository\Repository;
use Carbon\Carbon;
use WsSms;
use ApiSms;

class SmsRepository extends Repository
{
    protected $model = Sms::class;

    /**
     * 搜索短信
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search(array $form)
    {
        $resource = $this->model();
        if(isset($form['id'])) $resource->where('id', '=', $form['id']);
        if(isset($form['content'])) $resource->where('content', '%'.$form['content'].'%');
        if(isset($form['sender'])) $resource->where('sender', '%'.$form['sender'].'%');
        if(isset($form['ip'])) $resource->where('ip', '%'.$form['ip'].'%');
        if(isset($form['target'])) $resource->targets()->where('mobile', '%'.$form['target'].'%');
        if(isset($form['created_at_start']) && isset($form['created_at_end'])) {
            $resource->whereBetween('created_at', [
                Carbon::parse($form['created_at_start'])->toDatetimeString(),
                Carbon::parse($form['created_at_end'])->modify('+1 day')->toDatetimeString()
            ]);
        }
        
        return $resource->get();
    }

    /**
     * 添加新短信
     *
     * @param  array    $form
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function add(array $form)
    {
        try {
            if ( is_array($form['targets']) ) {
                $targets_arr = $form['targets'];
                $targets_str = implode(',', $targets_arr);
            } elseif ( is_string($form['targets']) ) {
                $targets_str = $form['targets'];
                $targets_arr = explode(',', $targets_str);
            } else {
                throw new \Exception('Invalid targets! Require [array] or [string], get [' . gettype($form['targets']) .']');
            }

            $result = ApiSms::send($targets_arr, $form['content']);
            // $result = WsSms::send($targets_str, $form['content']);
        } catch(\Exception $e) {
            \Log::warning('SMS Exception: '. $e->getMessage() );
            throw $e;
        }

        $resource = $this->createModel();

        $resource->content = $form['content'];
        $resource->sender = $form['sender'];
        $resource->result = $result['errcode'];
        $resource->ip = $form['ip'];

        $resource->save();

        foreach($targets_arr as $target) {
            $resource->targets()->create([
                'mobile' => $target
            ]);
        }

        return $resource->save() ? $resource : false;
    }
}