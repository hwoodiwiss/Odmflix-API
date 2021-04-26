<?php

namespace OdmflixApi;

include_once __DIR__ . '/Interface/IResult.php';

class OkResult implements IResult
{
	private string $stringTypeName;
	private string $intTypeName;
	private string $floatTypeName;
	private string $objectTypeName;
	private string $arrayTypeName;

	public function __construct(private mixed $data = null){
		$this->stringTypeName = gettype('');
		$this->intTypeName = gettype(0);
		$this->floatTypeName = gettype(0.1);
		$this->objectTypeName = gettype(new class {});
		$this->arrayTypeName = gettype([]);
	}

	/**
	 * function StatusCode
	 *
	 * @return int Status Code to send to user
	 */
	function StatusCode(): int {
		return 200;
	}
	
	/**
	 * function Body
	 *
	 * @return string|null Data to return to the user
	 */
	function Body(): ?string {
		$outBody = match(gettype($this->data)) {
			$this->stringTypeName => $this->data,
			$this->intTypeName => $this->data,
			$this->floatTypeName => $this->data,
			$this->objectTypeName => json_encode($this->data),
			$this->arrayTypeName => json_encode($this->data),
			default => null
		};
		return $outBody;
	}
}