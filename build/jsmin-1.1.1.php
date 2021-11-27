<?php

class JSMin
{
    public const ORD_LF = 10;
    public const ORD_SPACE = 32;

    protected $a = '';
    protected $b = '';
    protected $input = '';
    protected $inputIndex = 0;
    protected $inputLength = 0;
    protected $lookAhead;
    protected $output = '';

    // -- Public Instance Methods ------------------------------------------------

    public function __construct($input)
    {
        $this->input = str_replace("\r\n", "\n", $input);
        $this->inputLength = strlen($this->input);
    }

    // -- Public Static Methods --------------------------------------------------

    public static function minify($js)
    {
        $jsmin = new JSMin($js);

        return $jsmin->min();
    }

    // -- Protected Instance Methods ---------------------------------------------

    protected function action($d)
    {
        switch ($d) {
      case 1:
        $this->output .= $this->a;

        // no break
      case 2:
        $this->a = $this->b;

        if ("'" === $this->a || '"' === $this->a) {
            while (true) {
                $this->output .= $this->a;
                $this->a = $this->get();

                if ($this->a === $this->b) {
                    break;
                }

                if (ord($this->a) <= self::ORD_LF) {
                    throw new JSMinException('Unterminated string literal.');
                }

                if ('\\' === $this->a) {
                    $this->output .= $this->a;
                    $this->a = $this->get();
                }
            }
        }

        // no break
      case 3:
        $this->b = $this->next();

        if ('/' === $this->b && (
            '(' === $this->a || ',' === $this->a || '=' === $this->a
            || ':' === $this->a || '[' === $this->a || '!' === $this->a
            || '&' === $this->a || '|' === $this->a || '?' === $this->a
        )) {
            $this->output .= $this->a.$this->b;

            while (true) {
                $this->a = $this->get();

                if ('/' === $this->a) {
                    break;
                }
                if ('\\' === $this->a) {
                    $this->output .= $this->a;
                    $this->a = $this->get();
                } elseif (ord($this->a) <= self::ORD_LF) {
                    throw new JSMinException('Unterminated regular expression '.
                  'literal.');
                }

                $this->output .= $this->a;
            }

            $this->b = $this->next();
        }
    }
    }

    protected function get()
    {
        $c = $this->lookAhead;
        $this->lookAhead = null;

        if (null === $c) {
            if ($this->inputIndex < $this->inputLength) {
                $c = $this->input[$this->inputIndex];
                ++$this->inputIndex;
            } else {
                $c = null;
            }
        }

        if ("\r" === $c) {
            return "\n";
        }

        if (null === $c || "\n" === $c || ord($c) >= self::ORD_SPACE) {
            return $c;
        }

        return ' ';
    }

    protected function isAlphaNum($c)
    {
        return ord($c) > 126 || '\\' === $c || 1 === preg_match('/^[\w\$]$/', $c);
    }

    protected function min()
    {
        $this->a = "\n";
        $this->action(3);

        while (null !== $this->a) {
            switch ($this->a) {
        case ' ':
          if ($this->isAlphaNum($this->b)) {
              $this->action(1);
          } else {
              $this->action(2);
          }

          break;
        case "\n":
          switch ($this->b) {
            case '{':
            case '[':
            case '(':
            case '+':
            case '-':
              $this->action(1);

              break;
            case ' ':
              $this->action(3);

              break;
            default:
              if ($this->isAlphaNum($this->b)) {
                  $this->action(1);
              } else {
                  $this->action(2);
              }
          }

          break;
        default:
          switch ($this->b) {
            case ' ':
              if ($this->isAlphaNum($this->a)) {
                  $this->action(1);

                  break;
              }

              $this->action(3);

              break;
            case "\n":
              switch ($this->a) {
                case '}':
                case ']':
                case ')':
                case '+':
                case '-':
                case '"':
                case "'":
                  $this->action(1);

                  break;
                default:
                  if ($this->isAlphaNum($this->a)) {
                      $this->action(1);
                  } else {
                      $this->action(3);
                  }
              }

              break;
            default:
              $this->action(1);

              break;
          }
      }
        }

        return $this->output;
    }

    protected function next()
    {
        $c = $this->get();

        if ('/' === $c) {
            switch ($this->peek()) {
        case '/':
            while (true) {
                $c = $this->get();

                if (ord($c) <= self::ORD_LF) {
                    return $c;
                }
            }

          // no break
        case '*':
          $this->get();

            while (true) {
                switch ($this->get()) {
              case '*':
                if ('/' === $this->peek()) {
                    $this->get();

                    return ' ';
                }

                break;
              case null:
                throw new JSMinException('Unterminated comment.');
            }
            }

          // no break
        default:
          return $c;
      }
        }

        return $c;
    }

    protected function peek()
    {
        $this->lookAhead = $this->get();

        return $this->lookAhead;
    }
}

// -- Exceptions ---------------------------------------------------------------
class JSMinException extends Exception
{
}
