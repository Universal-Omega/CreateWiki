<?php

declare( strict_types=1 );

namespace NoOptionalParamPlugin;

use Phan\CodeBase;
use Phan\Language\Element\Func;
use Phan\Language\Element\Method;
use Phan\PluginV3;
use Phan\PluginV3\AnalyzeFunctionCapability;
use Phan\PluginV3\AnalyzeMethodCapability;

final class NoOptionalParamPlugin extends PluginV3 implements
	AnalyzeFunctionCapability,
	AnalyzeMethodCapability
{

	public static function getAnalyzeFunctionCallClosures( CodeBase $code_base ): array {
		return [];
	}

	public function analyzeFunction(
		CodeBase $code_base,
		Func $function
	): void {
		foreach ( $function->getParameterList() as $parameter ) {
			if ( $parameter->isOptional() ) {
				$this->emitPluginIssue(
					$code_base,
					$function->getContext(),
					'PhanPluginOptionalParameterFound',
					'Function {FUNCTION} has an optional parameter ${PARAMETER}',
					[ $function->getName(), $parameter->getName() ]
				);
			}
		}
	}

	public function analyzeMethod(
		CodeBase $code_base,
		Method $method
	): void {
		foreach ( $method->getParameterList() as $parameter ) {
			if ( $parameter->isOptional() ) {
				$this->emitPluginIssue(
					$code_base,
					$method->getContext(),
					'PhanPluginOptionalParameterFound',
					'Function {FUNCTION} has an optional parameter ${PARAMETER}',
					[ $method->getName(), $parameter->getName() ]
				);
			}
		}
	}

	public function shouldAnalyzeFunction( CodeBase $code_base, FunctionInterface $function ): bool {
		// Always analyze
		return true;
	}

	public static function getIssueSuppressionList(): array {
		return [ 'PhanPluginOptionalParameterFound' ];
	}
}

// Every plugin needs to return an instance of itself at the
// end of the file in which it's defined.
return new NoOptionalParamPlugin();
