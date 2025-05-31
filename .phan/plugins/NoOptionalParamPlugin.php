<?php

declare( strict_types=1 );

namespace NoOptionalParamPlugin;

use ast\Node;
use Phan\AST\ASTVisitor;
use Phan\CodeBase;
use Phan\Language\Element\FunctionInterface;
use Phan\Language\Element\Method;
use Phan\Language\Element\Func;
use Phan\Plugin\PluginV3;
use Phan\Plugin\Internal\IssueEmitters;
use Phan\Issue;

final class NoOptionalParamPlugin extends PluginV3
{
    public static function getAnalyzeFunctionCallClosures(CodeBase $code_base): array
    {
        return [];
    }

    public function analyzeFunctionDefinition(
        CodeBase $code_base,
        FunctionInterface $function
    ): void {
        foreach ($function->getParameterList() as $parameter) {
            if ($parameter->isOptional()) {
                $this->emitPluginIssue(
                    $code_base,
                    $function->getContext(),
                    'PhanPluginOptionalParameterFound',
                    'Function {FUNCTION} has an optional parameter ${PARAMETER}',
                    [$function->getName(), $parameter->getName()]
                );
            }
        }
    }

    public function shouldAnalyzeFunction(CodeBase $code_base, FunctionInterface $function): bool
    {
        return true; // Always analyze
    }

    public static function getIssueSuppressionList(): array
    {
        return ['PhanPluginOptionalParameterFound'];
    }
}
