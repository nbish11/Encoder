<?php

/**
 * Encodes strings into different encoding standards.
 *
 * PHP version 5
 * 
 * Copyright (C) 2013  Nathan Bishop
 *
 * LICENSE: This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author     Nathan Bishop <nbish11@hotmail.com>
 * @version    0.0.1
 * @copyright  2013 Nathan Bishop
 * @license    GPLv2
 * @link       https://github.com/nbish11/Encoder
 */

class Encoder
{
    /**
     * Base64 encoding is the scheme used to transmit binary data. Base64 
     * processes data as 24-bit groups, mapping this data to four encoded 
     * characters. It is sometimes referred to as 3-to-4 encoding. Each 6 
     * bits of the 24-bit group is used as an index into a mapping table 
     * (the base64 alphabet) to obtain a character for the encoded data. 
     * The encoded data has line lengths limited to 76 characters. The 
     * characters used in base64 encoding, the base64 alphabet, include none 
     * of the special characters of importance to SMTP or the hyphen used 
     * with MIME boundary strings.
     * 
     * @link http://msdn.microsoft.com/en-us/library/ms526568%28v=exchg.10%29.aspx
     */
    const BASE_64 = 'base64';
    
    
    /**
     * Quoted-printable encoding is used where data is mostly US-ASCII text. 
     * It allows for 8-bit characters to be represented as their hexadecimal 
     * values. For instance, a new line can be forced by using the following 
     * string: "=0D=0A". Line lengths are limited to 76 characters. Using an 
     * equal sign as the last character on the line as a "soft" line break 
     * accommodates longer lines. The 76-character limit does not include the 
     * CRLF sequence or the equal sign.
     * 
     * Any character, except the CRLF sequence, can be represented by an equal 
     * sign followed by a two-digit hexadecimal representation. This is 
     * especially useful in getting mostly text messages to pass reliably through 
     * gateways such as EBCDIC where such characters as "{" and "}" have special 
     * meaning.
     * 
     * @link http://msdn.microsoft.com/en-us/library/ms526941%28v=exchg.10%29.aspx
     */
    const QUOTED_PRINTABLE = 'quoted-printable';
    
    
    /**
     * The 7bit is the most fundamental message encoding. Actually, 7bit 
     * is not encoded; 7bit encoded files are files that use only 7-bit 
     * characters and have lines no longer than 1000 characters. CR (carriage 
     * return) and LF (line feed) characters can only occur as pairs to limit 
     * the line length to 1000 characters, including the CRLF pair. NULL 
     * characters are not allowed. 7bit encoded files need no encoding or 
     * decoding. This is the default.
     * 
     * @link http://msdn.microsoft.com/en-us/library/ms526290%28v=exchg.10%29.aspx
     */
    const SEVEN_BIT = '7bit';
    
    
    /**
     * 8bit encoding has the same line-length limitations as the 7bit encoding. 
     * It allows 8bit characters. No encoding or decoding is required for 8bit 
     * files. Since not all MTAs can handle 8bit data, the 8bit encoding is not 
     * a valid encoding mechanism for Internet mail.
     * 
     * @link http://msdn.microsoft.com/en-us/library/ms526992%28v=exchg.10%29.aspx
     */
    const EIGHT_BIT = '8bit';
    
    
    /**
     * Binary encoding is simply unencoded binary data. It has no line-length 
     * limitations. Binary encoded messages are not valid Internet messages.
     * 
     * @link http://msdn.microsoft.com/en-us/library/ms527563%28v=exchg.10%29.aspx
     */
    const BINARY = 'binary';
    
    
    /**
     * Encodes a string to the specified format.
     * 
     * @param string  $input 
     * @param string  $scheme 
     * @param integer $length  
     * 
     * @return string
     */
    public function encode($input, $scheme, $length = 75)
    {
        $length = (integer) $length;
    
        switch ($scheme) {
            case self::BASE_64:
                return $this->base64_encode($input, $length);
                
            case self::QUOTED_PRINTABLE:
                return $this->quoted_printable_encode($input, $length);
                
            case self::SEVEN_BIT:
                return false;
                
            case self::EIGHT_BIT:
                return false;
                
            case self::BINARY:
                return false;
                
            default:
                throw new InvalidArgumentException('The encoding scheme "'.$scheme.'" is not supported.');
        }
    }
    
    
    /**
     * Decodes a string from the specified encoding scheme.
     * 
     * @param string $input 
     * @param string $scheme 
     * 
     * @return string
     */
    public function decode($input, $scheme)
    {
        switch ($scheme) {
            case self::BASE_64:
                return base64_decode($input);
                
            case self::QUOTED_PRINTABLE:
                return quoted_printable_decode($input);
                
            case self::SEVEN_BIT:
                return false;
                
            case self::EIGHT_BIT:
                return false;
                
            case self::BINARY:
                return false;
                
            default:
                throw new InvalidArgumentException('The encoding scheme "'.$scheme.'" is not supported.');
        }
    }
    
    
    /**
     * Essentially the base base64_encode() function wrapped in the 
     * chunk_split() function for variable line lengths.
     * 
     * @param string  $input 
     * @param integer $length  
     * 
     * @return string
     */
    private function base64_encode($input, $length)
    {
        return chunk_split(base64_encode($input), $length, "\r\n");
    }
    
    
    /**
     * Replicates the behavior of imap_8bit() or quoted_printable_encode() 
     * but allows for a variable line length.
     * 
     * @link http://php.net/manual/en/function.imap-8bit.php#Hcom61216
     * 
     * @param string  $input 
     * @param integer $length  
     * 
     * @return string
     */
    private function quoted_printable_encode($input, $length)
    {
        $lines = explode(chr(13) . chr(10), $input);

        for ($i = 0; $i < count($lines); $i++) {
        
            $line =& $lines[$i];
            
            if (strlen($line) === 0) {
                continue;
            }

            $regex = '/[^\x20\x21-\x3C\x3E-\x7E]/e';
            $replace = 'sprintf("=%02X", ord("$0"));';
            $line = preg_replace($regex, $replace, $line); 
            $iLength = strlen($line);
            $iLastChar = ord($line{$iLength-1});
 
            if ( ! ($i == count($lines) -1)) {
                if (($iLastChar == 0x09) || ($iLastChar == 0x20)) {
                    $line{$iLength-1} = '=';
                    $line .= ($iLastChar == 0x09) ? '09' : '20';
                }
            }
            
            $line = str_replace(' =0D', '=20=0D', $line);
            
            preg_match_all('/.{1,'.($length-2).'}([^=]{0,2})?/', $line, $aMatch);

            $line = implode('=' . chr(13) . chr(10), $aMatch[0]);
        }

        return implode(chr(13) . chr(10), $lines);
    }
}
