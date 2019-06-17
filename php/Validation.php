<?php
    
    /**
     * Validation 
     *
     * Simple PHP validation class
     *
     * Modified 
     * Original Code From:
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @copyright (c) 2016, Davide Cesarano
     * @license https://github.com/davidecesarano/Validation/blob/master/LICENSE MIT License
     * @link https://github.com/davidecesarano/Validation
     */

    class Validation {

        /**
         * Array for storing regex
         * 
         * @var array $patterns
         */
        public $patterns = array(

            'contact-name'    => "[^@$%]+",            
            'text'           => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+'            
 
        );

        /**
         * Array for storing validation Errors
         * 
         * @var array $errors
         */
        public $errors = array();      
        
        /**
         * General function 
         * sanitize the value to prevent XSS attacks
         *
         * @param string $string
         * @return $string
         */
        public function sanitize($string){
            $string = trim($string); //remove the space 
            $string = stripslashes($string); //remove the backslash '\'
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

        }  
     

        /**
         * Use pattern apply to regex to check with preg_match()
         * 
         * @param string $name pattern name
         * @return this
         */
        public function pattern($name){
            //put in the regular expression pattern
            $regex = '/^('. $this->patterns[$name] . ')$/u';
            //sanitize the input
            //$current_value = $this->sanitize($this->value);

            if($this->value != '' && !preg_match($regex, $this->value)){
                $this->errors[] = 'Invalid Format of '.$this->field_name;
            }
    
            return $this;
            
        }        

        /**
         * Validate if the e-mail address is valid using PHP filter_var()         
         *
         * @param mixed $value
         * @return this
         */
        public function is_email($value){
            $email = $this->sanitize($value);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $this->errors[] = 'Invalid Email Address';
            }
            return $this;
        }        

        /**
         * The Name of the field
         * 
         * @param string $name
         * @return this
         */
        public function field_name($name){
            
            $this->field_name = $name;
            return $this;
        
        }

        /**
         * Value of the field
         * 
         * @param mixed $value
         * @return this
         */
        public function value($value){
            
            $this->value = $value;
            return $this;
        
        }

        /**
         * Field Required
         * 
         * @return this
         */
        public function required(){
            if($this->value == '' || $this->value == null){
                $this->errors[] = 'Field: '. $this->field_name.' Is Required.';
            }            
            return $this;
        }

        /**
         * Return true if all data is valid
         * 
         * @return boolean
         */
        public function isSuccess(){
            if(empty($this->errors)) return true;
        }

        /**
         * Validation errors
         * 
         * @return array $this->errors
         */
        public function getErrors(){
            if(!$this->isSuccess()) return $this->errors;
        }

        /**
         * Display errors in HTML format
         * 
         * @return string $html
         */
        public function displayErrors(){
            
            $html = '<ul>';
                foreach($this->getErrors() as $error){
                    $html .= '<li>'.$error.'</li>';
                }
            $html .= '</ul>';
            
            return $html;
            
        }


    }