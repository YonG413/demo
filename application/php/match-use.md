#### php8的match，如何用？

PHP 8 引入了新的 `match` 表达式，它是 `switch` 语句的一种替代方式，用于更简洁和直观地处理多个条件分支。`match` 表达式允许使用严格的值比较和返回值，以下是 10 个使用 `match` 表达式的示例：

1. 基本用法：

```php
$color = 'red';

$result = match ($color) {
    'red' => 'Apple',
    'yellow' => 'Banana',
    'green' => 'Kiwi',
    default => 'Unknown fruit',
};

echo $result; // Output: "Apple"
```

2. 使用条件运算符：

```php
$number = 7;

$result = match (true) {
    $number > 0 => 'Positive',
    $number < 0 => 'Negative',
    default => 'Zero',
};

echo $result; // Output: "Positive"
```

3. 使用多个条件：

```php
$letter = 'a';

$result = match ($letter) {
    'a', 'e', 'i', 'o', 'u' => 'Vowel',
    default => 'Consonant',
};

echo $result; // Output: "Vowel"
```

4. 使用范围条件：

```php
$score = 85;

$result = match (true) {
    $score >= 90 => 'A',
    $score >= 80 => 'B',
    $score >= 70 => 'C',
    $score >= 60 => 'D',
    default => 'F',
};

echo $result; // Output: "B"
```

5. 多个匹配条件执行相同逻辑：

```php
$fruit = 'apple';

match ($fruit) {
    'apple', 'banana', 'orange' => {
        echo "This is a fruit.";
    },
    'carrot', 'broccoli' => {
        echo "This is a vegetable.";
    },
};
// Output: "This is a fruit."
```

6. 空合并运算符：

```php
$userName = null;

$result = match ($userName ?? '') {
    'admin' => 'Administrator',
    'guest' => 'Guest',
    default => 'Unknown user',
};

echo $result; // Output: "Unknown user"
```

7. 嵌套使用：

```php
$value = 10;

$result = match (true) {
    $value > 0 => match (true) {
        $value < 5 => 'Less than 5',
        $value > 5 => 'Greater than 5',
        default => 'Equal to 5',
    },
    default => 'Non-positive',
};

echo $result; // Output: "Greater than 5"
```

8. 使用条件组合：

```php
$day = 'Saturday';
$weather = 'sunny';

$result = match (true) {
    ($day === 'Saturday' || $day === 'Sunday') && $weather === 'sunny' => 'Outdoor activities',
    ($day === 'Saturday' || $day === 'Sunday') && $weather === 'rainy' => 'Indoor activities',
    default => 'Workday',
};

echo $result; // Output: "Outdoor activities"
```

9. 使用默认值：

```php
$value = 'x';

$result = match ($value) {
    1, 2, 3 => 'Small',
    4, 5, 6 => 'Medium',
    7, 8, 9 => 'Large',
    default => 'Unknown',
};

echo $result; // Output: "Unknown"
```

10. 使用点运算符：

```php
$grade = 85;

$result = match (true) {
    $grade < 60 => 'Failing',
    $grade >= 60 && $grade < 70 => 'D',
    $grade >= 70 && $grade < 80 => 'C',
    $grade >= 80 && $grade < 90 => 'B',
    $grade >= 90 => 'A',
};

echo $result; // Output: "B"
```

这些例子展示了 `match` 表达式在 PHP 8 中的多种用法，使得代码更简洁、易读和高效。在实际开发中，根据具体场景，您可以灵活使用 `match` 表达式来简化条件分支的处理。