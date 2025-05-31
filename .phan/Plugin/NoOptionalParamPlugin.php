<?php

declare( strict_types=1 );

namespace NoOptionalParamPlugin;

use Phan\CodeBase;
use Phan\Language\Element\FunctionInterface;
use Phan\PluginV3;

final class NoOptionalParamPlugin extends PluginV3 {

	public static function getAnalyzeFunctionCallClosures( CodeBase $code_base ): array {
		return [];
	}

	public function analyzeFunctionDefinition(
		CodeBase $code_base,
		FunctionInterface $function
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
