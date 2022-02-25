<?php
    /*
     * Storage area for client's info
     */
    class ClientInfo
    {
        // Properties
        public $u_ID = "";
        public $u_name = "";
        public $firstName = "";
        public $surname = "";
        public $phoneNo = "";
        public $email = "";

        /* Methods/Functions */

        // Getter
        function get_uID()
        {
            return $this->u_ID;
        }
        function get_name()
        {
            return $this->firstName;
        }
        function get_surname()
        {
            return $this->surname;
        }
        function get_phoneNo()
        {
            return $this->phoneNo;
        }
        function get_email()
        {
            return $this->email;
        }

        // Setter
        function set_uID($new_uid)
        {
            if(!$new_uid)
            {
                $this->u_ID = $new_uid;
            }
        }
        function set_name($new_firstname="", $new_lastname="")
        {
            if(!$new_firstname == "")
            {
                $this->firstName = $new_firstname;
            }
            if(!$new_lastname == "")
            {
                $this->surname = $new_lastname;
            }
        }
        function set_phone_Number($new_phone_No="")
        {
            if(!$new_phone_No == "")
            {
                $this->phoneNo = $new_phone_No;
            }
        }
        function set_email($new_email="")
        {
            if(!$new_email == "")
            {
                $this->email = $new_email;
            }
        }
    }
?>
