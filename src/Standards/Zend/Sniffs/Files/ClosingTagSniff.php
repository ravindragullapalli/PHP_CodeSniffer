<?php
/**
 * Checks that the file does not end with a closing tag.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Standards\Zend\Sniffs\Files;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ClosingTagSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_OPEN_TAG);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // Find the last non-empty token.
        $tokens = $phpcsFile->getTokens();
        for ($last = ($phpcsFile->numTokens - 1); $last > 0; $last--) {
            if (trim($tokens[$last]['content']) !== '') {
                break;
            }
        }

        if ($tokens[$last]['code'] === T_CLOSE_TAG) {
            $error = 'A closing tag is not permitted at the end of a PHP file';
            $fix   = $phpcsFile->addFixableError($error, $last, 'NotAllowed');
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($last, '');
            }

            $phpcsFile->recordMetric($stackPtr, 'PHP closing tag at EOF', 'yes');
        } else {
            $phpcsFile->recordMetric($stackPtr, 'PHP closing tag at EOF', 'no');
        }

        // Ignore the rest of the file.
        return ($phpcsFile->numTokens + 1);

    }//end process()


}//end class
