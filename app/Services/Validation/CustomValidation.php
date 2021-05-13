<?php namespace App\Services\Validation;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;

// It's important to extend the Validator class
// By doing that we make sure we keep all the other rules as well!
// Now if you want to override a rule you can do that here as well
// For now we will only focus on creating rules
class CustomValidation extends Validator {

    // Laravel keeps a certain convention for rules
    // So the function is called validateGreaterThen    
    // Then the rule is greater_then

    // A validation rule accepts three parameters
    // $attribute This is the name of the input
    // $value This is the value of the input
    // $parameters This is a parameter for the rule, so greater_then:1,2 has two parameters
    // the $parameters are returned as an array so for the first parameter: $parameters[0]

    // Now that we know how a rule works let's create one
    /**
     * $attribute Input name
     * $value Input value
     * $parameters Table, field1
     */
    public function validateUniqueWith($attribute, $value, $parameters,$validator)
    {
		
		
        // Now that we have our data we can check for the data

        // We first grab the correct table which is passed to the function
        // Now we need to do some checking using Eloquent
        // If you don't understand this, please let me know
        $result = DB::table($parameters[0])->where(function($query) use ($attribute, $value, $parameters) {
			
            	$query->where($attribute, '=', $value);
			    $query->where($parameters[1], '=', request($parameters[1]));
				$query->whereDate($parameters[2], '=', request($parameters[2]));
				
				$query->where(function($q) use($parameters){
					$q->where($parameters[3], '=', request($parameters[3]));
					$q->where($parameters[4], '=', request($parameters[4]));
				});
				if(isset($parameters[5])){
					$query->where('id','!=',$parameters[5]);
				}
				
				
        })->first();

        // Now we check if we have a record
        // If we do have a record we return false and the validation will fail
        // If we can't find a record we return true and the validation will succeed
        return $result ? false : true;
    }

    // If you need more examples, let me know ;)
}