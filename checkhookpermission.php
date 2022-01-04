<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class CheckHookPermission extends Module
{
    public function __construct()
    {
        $this->name = 'checkhookpermission';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = '202 ecommerce';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => '1.7',
        ];

        parent::__construct();

        $this->displayName = $this->l('Check Hook Permission');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('actionProductSave')
            && $this->registerHook('actionOrderStatusUpdate');
    }

    public function hookActionProductUpdate()
    {
        $this->addIdProfileInLogs(__FUNCTION__);
    }

    public function hookActionProductSave()
    {
        $this->addIdProfileInLogs(__FUNCTION__);
    }

    public function hookActionOrderStatusUpdate()
    {
        $this->addIdProfileInLogs(__FUNCTION__);
    }

    public function addIdProfileInLogs($hookName)
    {
        $id_profile = 'null';
        $employee = Context::getContext()->employee;
        if ($employee instanceof EmployeeCore && $employee->id_profile) {
            $id_profile = $employee->id_profile;
        }
        $text = sprintf('You are in %s. Your profile id: %s', $hookName, $id_profile);

        PrestaShopLogger::addLog($text);
    }

    public function uninstall()
    {
        return $this->unregisterHook('actionProductUpdate')
            && $this->unregisterHook('actionProductSave')
            && $this->unregisterHook('actionOrderStatusUpdate')
            && parent::uninstall();
    }
}