<?php 
	  
	/*
    |--------------------------------------------------------------------------
    | Custom Payment gateway configuration
    |--------------------------------------------------------------------------
    |
    |	Example format for custom payment configuration in .env file:
    |
    |	MEMBER_CUSTOM_PG=MEM_ID1|URL1|PREFIX1,MEM_ID2|URL2|PREFIX2
    |	EX: MEMBER_CUSTOM_PG=1|https://some_url|Rec_some_prefix_,2|https://other_url|Rec_other_prefix_
	|   
	|	
    |
    */

	return [
		'MEMBER_CUSTOM_PG' => env('MEMBER_CUSTOM_PG')? collect(explode(',', env('MEMBER_CUSTOM_PG')))->pipe(function($collection) {
		    $arr = [];
	        foreach ($collection as $item) {
	            list($pg_member_id, $pg_url, $pg_prefix) = explode('|', $item);
	            // $arr[$pg_member_id]['pg_member_id'] = $pg_member_id;
	            $arr[$pg_member_id]['pg_url'] = $pg_url;
	            $arr[$pg_member_id]['pg_prefix'] = $pg_prefix;
	        }

	        return collect($arr);
		})->toArray() : null
	];