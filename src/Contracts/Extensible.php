<?php 
namespace Core\Contracts;

interface Extensible
{
	 
    /**
     * Name of pacakge.
     * 
     * @return string
     */
    public function name();

    /**
     * Display name of package.
     * 
     * @return string
     */
    public function label();

    /**
     * Description of package.
     * 
     * @return string
     */
    public function description();

    /**
     * Current version of package.
     * 
     * @return string
     */
    public function version();

    /**
     * Plugin author fullname.
     * 
     * @return string
     */
    public function author();

    /**
     * Plugin author email.
     * 
     * @return string
     */
    public function email();
}
