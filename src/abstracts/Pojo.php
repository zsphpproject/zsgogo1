<?php
declare(strict_types=1);

namespace ZsGoGo\abstracts;

use think\contract\Arrayable;
use think\Exception;
use think\helper\Str;
use ReflectionClass;
use ReflectionProperty;
use think\Request;
use ZsGoGo\constant\ErrorNums;
use ZsGoGo\exception\PojoException;


/**
 *
 */
abstract class Pojo implements Arrayable {


    /**
     * @var array $data 数据
     */
    private $data = [];

    /**
     * @var array $validates 验证器
     */
    protected $validates = [];

    /**
     * @var bool $autoValidate 自动验证
     */
    protected $autoValidate = true;

    /**
     * @var array $notFilterField 无需全局过滤的字段
     */
    protected $notFilterField = [];

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    public function toArray(): array {
        $properties = $this->reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $getDataFuncName = 'get' . ucfirst($propertyName);
            $this->data[Str::snake($propertyName)] = $this->$getDataFuncName();
        }
        return $this->data;
    }


    public function __construct(Request $request, array $param = []) {
        if (empty($param)) {
            $inputData = $request->getInput();
            $inputData = $this->fitterData(json_decode($inputData, true) ?? []);
        } else {
            $inputData = $param;
        }
        $this->reflectionClass = new ReflectionClass($this);
        $this->setData($inputData);
        if ($this->autoValidate) {
            $this->validate();
        }
    }


    public function validate() {
        foreach ($this->validates as $validate => $scene) {
            if (is_string($scene)) {
                validate($validate)->scene($scene)->check($this->toArray());
            } else if (is_array($scene)) {
                foreach ($scene as $item) {
                    validate($validate)->scene($item)->check($this->toArray());
                }
            }
        }
    }


    public function fitterData(array $params): array {
        foreach ($params as $paramKey => $paramValue) {
            if (!in_array($paramKey, $this->notFilterField)) {
                $param = app()->request->only([$paramKey], [$paramKey => $paramValue]);
                $params[$paramKey] = $param[$paramKey];
            }
        }
        return $params;
    }


    private function setData($inputData) {
        $properties = $this->reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        foreach ($properties as $property) {
            $propertySnakeName = Str::snake($property->getName());
            if (isset($inputData[$propertySnakeName])) {
                $propertyValue = $inputData[$propertySnakeName];
                $propertyName = $property->getName();
                $setDataFuncName = 'set' . ucfirst($propertyName);
                if (!$this->reflectionClass->hasMethod($setDataFuncName)) {
                    throw new PojoException('method ' . $this->reflectionClass->getName() . '::' . $setDataFuncName . ' not exists!', ErrorNums::METHOD_NOT_EXISTS);
                }
                $reflectionMethod = $this->reflectionClass->getMethod($setDataFuncName);
                if (!$reflectionMethod->isPublic()) {
                    throw new PojoException('method ' . $this->reflectionClass->getName() . '::' . $setDataFuncName . ' is not public!', ErrorNums::METHOD_NOT_PUBLIC);
                }
                $reflectionMethod->invokeArgs($this, [$propertyValue]);
            }
        }
    }
}