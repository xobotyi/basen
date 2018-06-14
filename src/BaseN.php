<?php
/**
 * @Author : a.zinovyev
 * @Package: basen
 * @License: http://www.opensource.org/licenses/mit-license.php
 */

namespace xobotyi\basen;

class BaseN
{
    protected const LCM = [1 => 1, 2 => 1, 3 => 3, 4 => 1, 5 => 5, 6 => 3, 7 => 7, 8 => 1];

    protected $alphabet;
    protected $alphabetMap;
    protected $bitsPerCharacter;
    protected $caseSensitive;
    protected $padCharacter;
    protected $padFinalBits;
    protected $padFinalGroup;
    protected $radix;
    protected $roughEncoding;

    public function __construct(string $alphabet, bool $caseSensitive = true, bool $padFinalBits = false, bool $padFinalGroup = false, string $padCharacter = '=') {
        $this->setCaseSensitive($caseSensitive)
             ->setAlphabet($alphabet)
             ->setPadCharacter($padCharacter)
             ->setPadFinalBits($padFinalBits)
             ->setPadFinalGroup($padFinalGroup);
    }

    public function getAlphabet() :string {
        return $this->alphabet;
    }

    public function setAlphabet(string $alphabet) :self {
        if ($alphabet === $this->alphabet) {
            return $this;
        }

        $alphabetLength = \strlen($alphabet);
        if ($alphabetLength < 2) {
            throw new \InvalidArgumentException('alphabet must contain at least 2 characters');
        }

        # determine how much bits per character will we need
        $bitsPerCharacter = 1;
        $radix            = 2;

        while ($bitsPerCharacter < 8 && $alphabetLength > $radix && ($radix <<= 1)) {
            $bitsPerCharacter++;
        }

        if ($bitsPerCharacter === 8) {
            throw new \InvalidArgumentException('given alphabet requires more than 8 bits peer character which is maximal');
        }

        $radix = $alphabetLength;

        $this->alphabet         = $alphabet;
        $this->alphabetMap      = null;
        $this->bitsPerCharacter = $bitsPerCharacter;
        $this->radix            = $radix;
        $this->roughEncoding    = $radix < 2 << $bitsPerCharacter - 1;

        return $this;
    }

    public function isCaseSensitive() :bool {
        return $this->caseSensitive;
    }

    public function setCaseSensitive(bool $caseSensitive) :self {
        $this->caseSensitive = $caseSensitive;

        return $this;
    }

    public function isPaddingFinalBits() :bool {
        return $this->padFinalBits;
    }

    public function isPaddingFinalGroup() :bool {
        return $this->padFinalGroup;
    }

    public function setPadFinalBits(bool $padFinalBits) :self {
        $this->padFinalBits = $padFinalBits;

        return $this;
    }

    public function setPadFinalGroup(bool $padFinalGroup) :self {
        if($this->padFinalGroup = $padFinalGroup){
            if ($this->padFinalGroup && ($this->caseSensitive ? strpos($this->alphabet, $this->padCharacter) : stripos($this->alphabet, $this->padCharacter)) !== false) {
                throw new \InvalidArgumentException('pad character can not be a member of alphabet');
            }
        }

        return $this;
    }

    public function getPadCharacter() :string {
        return $this->padCharacter;
    }

    public function setPadCharacter(string $padCharacter) :self {
        if ($padCharacter === $this->padCharacter) {
            return $this;
        }

        if (\strlen($padCharacter) !== 1) {
            throw new \InvalidArgumentException('pad character must be a single character string');
        }

        if ($this->padFinalGroup && ($this->caseSensitive ? strpos($this->alphabet, $padCharacter) : stripos($this->alphabet, $padCharacter)) !== false) {
            throw new \InvalidArgumentException('pad character can not be a member of alphabet');
        }

        $this->padCharacter = $padCharacter;

        return $this;
    }

    public function encode(string $rawString) :string {
        if (!$rawString) {
            return '';
        }

        if ($this->roughEncoding) {
            return $this->encodeRough($rawString);
        }

        $result = '';

        $rawBytes = \unpack('C*', $rawString);

        $charsPerByte = 8 / $this->bitsPerCharacter;
        $resultLength = \count($rawBytes) * $charsPerByte;

        $byte     = \array_shift($rawBytes);
        $bitsRead = 0;

        for ($i = 0; $i < $resultLength; $i++) {
            if ($bitsRead + $this->bitsPerCharacter > 8) {
                # not enough space for character in current byte
                # storing remaining bits before process next byte
                $overflowBitsCount = 8 - $bitsRead;
                $overflowBits      = $byte ^ ($byte >> $overflowBitsCount << $overflowBitsCount);

                $storeBitsCount = $this->bitsPerCharacter - $overflowBitsCount;

                # if it's the last bits to process
                if (empty($rawBytes)) {
                    if ($this->padFinalBits) {
                        $overflowBits <<= $storeBitsCount;
                    }

                    $result .= $this->alphabet[$overflowBits];

                    if ($this->padFinalGroup) {
                        $pads   = self::LCM[$this->bitsPerCharacter] * $charsPerByte - \ceil(\strlen($rawString) % self::LCM[$this->bitsPerCharacter] * $charsPerByte);
                        $result .= \str_repeat($this->padCharacter, $pads);
                    }

                    # everything padded, exiting the loop
                    break;
                }

                # get nex byte
                $byte     = \array_shift($rawBytes);
                $bitsRead = 0;
            }
            else {
                $overflowBits      = 0;
                $overflowBitsCount = 0;

                $storeBitsCount = $this->bitsPerCharacter;
            }

            # read only needed count of bits from current byte
            $bits = $byte >> 8 - ($bitsRead + $storeBitsCount);
            $bits ^= $bits >> $storeBitsCount << $storeBitsCount;

            $bitsRead += $storeBitsCount;
            if ($overflowBitsCount) {
                # add overflowed bits from previous byte
                $bits = ($overflowBits << $storeBitsCount) | $bits;
            }

            $result .= $this->alphabet[$bits];
        }

        return $result;
    }

    public function decode(string $encodedString) :string {
        if (!$encodedString) {
            return '';
        }

        if ($this->roughEncoding) {
            return $this->decodeRough($encodedString);
        }

        # prepare alphabet map if it wasn't yet
        if (!$this->alphabetMap) {
            $this->alphabetMap = \array_flip(\str_split($this->alphabet));
        }

        # remove padding characters
        if ($this->padFinalGroup) {
            $encodedString = \rtrim($encodedString, $this->padCharacter);
        }

        $lastIndex       = \strlen($encodedString) - 1;
        $rawString       = '';
        $byte            = 0;
        $bitsStoredCount = 0;

        for ($i = 0; $i <= $lastIndex; $i++) {
            # if encoding is case insensitive and character wasn't found
            # we have to try both cases
            if (!$this->caseSensitive && !isset($this->alphabetMap[$encodedString[$i]])) {
                # mostly, lowercase is used so it goes first
                if (isset($this->alphabetMap[$charLower = strtolower($encodedString[$i])])) {
                    # store value to avoid further case changing
                    $this->alphabetMap[$encodedString[$i]] = $this->alphabetMap[$charLower];
                }
                else if (isset($this->alphabetMap[$charUpper = strtoupper($encodedString[$i])])) {
                    $this->alphabetMap[$encodedString[$i]] = $this->alphabetMap[$charUpper];
                }
            }

            if (!isset($this->alphabetMap[$encodedString[$i]])) {
                throw new \InvalidArgumentException("Unable to decode string, character ${encodedString[$i]} is out of alphabet");
            }

            $bitsRequired = 8 - $bitsStoredCount;
            $bitsLeft     = $this->bitsPerCharacter - $bitsRequired;

            if ($bitsRequired > $this->bitsPerCharacter) {
                # left shift bits if they're not enough to complete the byte
                $bits            = $this->alphabetMap[$encodedString[$i]] << $bitsRequired - $this->bitsPerCharacter;
                $bitsStoredCount += $this->bitsPerCharacter;
            }
            else if ($i !== $lastIndex || $this->padFinalBits) {
                # right shift bits if they're too much to complete the byte
                $bits            = $this->alphabetMap[$encodedString[$i]] >> $bitsLeft;
                $bitsStoredCount = 8;
            }
            else {
                # final bits shouldn't be shifted
                $bits            = $this->alphabetMap[$encodedString[$i]];
                $bitsStoredCount = 8;
            }

            # push bits to byte
            $byte |= $bits;

            if ($bitsStoredCount === 8 || $i === $lastIndex) {
                # write the ready byte to string
                $rawString .= pack('C', $byte);

                if ($i !== $lastIndex) {
                    # start the new byte
                    $bitsStoredCount = $bitsLeft;
                    $byte            = ($this->alphabetMap[$encodedString[$i]] ^ ($bits << $bitsLeft)) << 8 - $bitsStoredCount;
                }
            }
        }

        return $rawString;
    }

    private function divMod(array &$bytes, int $radixFrom, int $radixTo, int $start = 0) :int {
        $size      = \count($bytes);
        $remainder = 0;

        for ($i = $start; $i < $size; $i++) {
            $temp      = ($remainder * $radixFrom) + $bytes[$i];
            $bytes[$i] = intdiv($temp, $radixTo);
            $remainder = $temp % $radixTo;
        }

        return $remainder;
    }

    public function encodeRough(string $rawString) :string {
        $encodedString = '';

        $bytes = \array_values(\unpack("C*", $rawString));
        $size  = \count($bytes);

        for ($i = 0; $i < $size;) {
            $encodedString = $this->alphabet[$this->divMod($bytes, 256, $this->radix, $i)] . $encodedString;

            if ($bytes[$i] == 0) {
                $i++;
            }
        }

        return $encodedString;
    }

    public function decodeRough(string $encodedString) :string {
        $rawStringBytes = [];

        # prepare alphabet map if it wasn't yet
        if (!$this->alphabetMap) {
            $this->alphabetMap = \array_flip(\str_split($this->alphabet));
        }

        $size  = \strlen($encodedString);
        $bytes = [];
        for ($i = 0; $i < $size; $i++) {
            # if encoding is case insensitive and character wasn't found
            # we have to try both cases
            if (!$this->caseSensitive && !isset($this->alphabetMap[$encodedString[$i]])) {
                # mostly, lowercase is used so it goes first
                if (isset($this->alphabetMap[$charLower = \strtolower($encodedString[$i])])) {
                    # store value to avoid further case changing
                    $this->alphabetMap[$encodedString[$i]] = $this->alphabetMap[$charLower];
                }
                else if (isset($this->alphabetMap[$charUpper = \strtoupper($encodedString[$i])])) {
                    $this->alphabetMap[$encodedString[$i]] = $this->alphabetMap[$charUpper];
                }
            }

            if (!isset($this->alphabetMap[$encodedString[$i]])) {
                throw new \InvalidArgumentException("Unable to decode string, character ${encodedString[$i]} is out of alphabet");
            }

            $bytes[] = $this->alphabetMap[$encodedString[$i]];
        }

        for ($i = 0; $i < $size;) {
            \array_unshift($rawStringBytes, $this->divMod($bytes, $this->radix, 256, $i));

            if ($bytes[$i] == 0) {
                $i++;
            }
        }

        while ($rawStringBytes && !$rawStringBytes[0]) {
            \array_shift($rawStringBytes);
        }

        return pack('C*', ...$rawStringBytes);
    }

    public function encodeInt(int $int) :string {
        $encodedString = '';

        while ($int >= $this->radix) {
            $left          = $int % $this->radix;
            $int           = ($int - $left) / $this->radix;
            $encodedString = $this->alphabet[$left] . $encodedString;
        };

        return $this->alphabet[$int] . $encodedString;
    }

    public function decodeInt(string $encodedInt) :int {
        if (!$this->alphabetMap) {
            $this->alphabetMap = \array_flip(\str_split($this->alphabet));
        }

        $res  = 0;
        $rank = $len = strlen($encodedInt) - 1;

        while ($rank >= 0) {
            $dec = $this->alphabetMap[$encodedInt[$rank]];

            if ($dec) {
                $res += pow($this->radix, $len - $rank) * $dec;
            }

            $rank--;
        }

        return (int)$res;
    }
}