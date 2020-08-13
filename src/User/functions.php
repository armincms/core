<?php
if (! function_exists('logged_admin')) { 
    /**
     * Get current logged admin.
     *
     * @param  void
     * @return \Illuminate\Auth\Authenticated
     */
    function logged_admin()
    {    
        return \Auth::guard('admin')->user();
    }
} 

if (! function_exists('logged_user')) { 
    /**
     * Get current logged user.
     *
     * @param  void
     * @return \Illuminate\Auth\Authenticated
     */
    function logged_user()
    {    
        return \Auth::guard('user')->user();
    }
} 

if (! function_exists('is_admin')) { 
    /**
     * There is logged admin.
     *
     * @param  void
     * @return boolean
     */
    function is_admin()
    {    
        return ! is_null(logged_admin());
    }
} 

if (! function_exists('admin_is')) { 
    /**
     * Checking that the current admin has role.
     *
     * @param  void
     * @return boolean
     */
    function admin_is(string $role)
    {     
        return (boolean) (is_admin() && logged_admin()->username == $role);
    }
} 

if (! function_exists('is_user')) { 
    /**
     * There is logged user.
     *
     * @param  void
     * @return boolean
     */
    function is_user()
    {    
        return ! is_null(logged_user());
    }
} 

if (! function_exists('user_is')) { 
    /**
     * Checking that the current user has role.
     *
     * @param  void
     * @return boolean
     */
    function user_is(string $role)
    {    
        return (boolean) (is_user() && logged_user()->username == $role);
        // return (boolean) (is_user() && logged_user()->hasRole($role));
    }
} 

if (! function_exists('is_superadmin')) { 
    /**
     * Checking that the current user is superadmin or not.
     *
     * @param  void
     * @return boolean
     */
    function is_superadmin()
    {    
        return admin_is('superadministrator');
    }
}

if (! function_exists('is_administrator')) { 
    /**
     * Checking that the current user is superadmin or not.
     *
     * @param  void
     * @return boolean
     */
    function is_administrator()
    {   
        return admin_is('administrator');
    }
}  
