<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = $this->route('tenant') ? $this->route('tenant')->id : null;
        $domainId = $this->route('tenant') && $this->route('tenant')->domains->first()
            ? $this->route('tenant')->domains->first()->id
            : null;
        $deniedUsernames = ['root', 'mysql.sys', 'mysql.infoschema', 'mysql.session', 'admin'];
        $maxFileSize = intval(env('LIMIT_SIZE_FILE')) > 0 ? intval(env('LIMIT_SIZE_FILE')) : 2048;

        return [
            'tenancy_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('t_tenants', 'name')->where(fn($query) => $query->where('delete_flg', 0))->ignore($tenantId),
            ],
            'tenancy_access_key' => 'nullable|string|max:255|min:8|regex:/^(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]+$/',
            'tenancy_hash_code' => 'nullable|string|max:255|min:8|regex:/^(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]+$/',
            'tenancy_db_connection' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $this->validateDatabaseConnection($attribute, $value, $fail);
                }
            ],
            'tenancy_db_host' => [
                'required',
                'string',
                'max:253',
                function ($attribute, $value, $fail) {
                    if (
                        !filter_var($value, FILTER_VALIDATE_IP) &&
                        !preg_match('/^(?!-)[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,})?$/', $value)
                    ) {
                        $fail('Host cơ sở dữ liệu không hợp lệ');
                    }
                }
            ],
            'tenancy_db_port' => 'required|integer|min:1|max:65535',
            'tenancy_db_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('t_tenants', 'tenancy_db_name')->where(fn($query) => $query->where('delete_flg', 0))->ignore($tenantId),
                function ($attribute, $value, $fail) {
                    $this->validateDbName($attribute, $value, $fail);
                }
            ],
            'tenancy_db_username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('t_tenants', 'tenancy_db_username')->where(fn($query) => $query->where('delete_flg', 0))->ignore($tenantId),
                function ($attribute, $value, $fail) use ($deniedUsernames) {
                    if (in_array(strtolower($value), $deniedUsernames)) {
                        $fail("Không được phép sử dụng tên tài khoản hệ thống '{$value}'.");
                        return;
                    }
                    $this->validateDbUser($attribute, $value, $fail);
                }
            ],
            'tenancy_db_password' => 'nullable|string|max:255|min:8|not_regex:/\s/|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]+$/|',
            'tenancy_db_password_confirm' => 'required_with:tenancy_db_password|same:tenancy_db_password',
            'tenancy_group' => 'nullable|exists:m_groups,id',
            'tenancy_admin' => 'nullable|exists:m_admin_tenants,id',
            'tenancy_logo' => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:{$maxFileSize}",
            'tenancy_tiktok_url' => 'nullable|string|max:255',
            'tenancy_fb_url' => 'nullable|string|max:255',
            'tenancy_ig_url' => 'nullable|string|max:255',
            'tenancy_address' => 'nullable|string|max:255',
            'tenancy_is_active' => 'required|boolean|in:0,1',
            'cropped_logo' => 'sometimes|string',
            'copy_from_tenant_id' => 'nullable|exists:t_tenants,id',
            'tenancy_domain' => [
                'required',
                'string',
                'max:255',
                Rule::unique('m_domains', 'domain')
                    ->where(fn($query) => $query->where('delete_flg', 0))
                    ->ignore($domainId),
                'regex:/^(?!:\/\/)([a-z0-9-]+)(\.[a-z0-9-]+)*$/',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^-|-$/', $value)) {
                        $fail('Tên miền không được bắt đầu hoặc kết thúc bằng dấu gạch nối (-).');
                    }
                    if (str_contains($value, '..')) {
                        $fail('Tên miền không được chứa dấu chấm liên tiếp.');
                    }
                    $labels = explode('.', $value);
                    foreach ($labels as $label) {
                        if (strlen($label) > 63) {
                            $fail('Mỗi phần của tên miền (phần giữa các dấu chấm) không được vượt quá 63 ký tự.');
                            break;
                        }
                    }
                },
            ],

        ];
    }



    public function messages(): array
    {
        return [
            'tenancy_name.required' => 'Vui lòng nhập tên cửa hiệu',
            'tenancy_name.unique' => 'Tên cửa hiệu đã tồn tại',
            'tenancy_name.max' => 'Tên cửa hiệu không được vượt quá 255 ký tự',
            'tenancy_access_key.min' => 'Access Key phải có ít nhất 8 ký tự',
            'tenancy_access_key.regex' => 'Access Key phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt, không được chứa khoảng trắng',
            'tenancy_access_key.max' => 'Access Key không được vượt quá 255 ký tự',
            'tenancy_hash_code.min' => 'Hash Code phải có ít nhất 8 ký tự',
            'tenancy_hash_code.regex' => 'Hash Code phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt, không được chứa khoảng trắng',
            'tenancy_hash_code.max' => 'Hash Code không được vượt quá 255 ký tự',
            'tenancy_db_connection.required' => 'Vui lòng chọn kết nối cơ sở dữ liệu',
            'tenancy_db_connection.max' => 'Kết nối cơ sở dữ liệu không được vượt quá 255 ký tự',
            'tenancy_db_host.required' => 'Vui lòng nhập host cơ sở dữ liệu',
            'tenancy_db_host.max' => 'Host cơ sở dữ liệu không được vượt quá 255 ký tự',
            'tenancy_db_port.required' => 'Vui lòng nhập cổng kết nối',
            'tenancy_db_port.integer' => 'Cổng kết nối phải là số nguyên',
            'tenancy_db_port.min' => 'Cổng kết nối phải từ 1',
            'tenancy_db_port.max' => 'Cổng kết nối phải từ 1 đến 65535',
            'tenancy_db_name.required' => 'Vui lòng nhập tên cơ sở dữ liệu',
            'tenancy_db_name.unique' => 'Tên cơ sở dữ liệu đã tồn tại',
            'tenancy_db_name.max' => 'Tên cơ sở dữ liệu không được vượt quá 255 ký tự',
            'tenancy_db_username.required' => 'Vui lòng nhập tên tài khoản cơ sở dữ liệu',
            'tenancy_db_username.unique' => 'Tên tài khoản cơ sở dữ liệu đã tồn tại',
            'tenancy_db_username.max' => 'Tên tài khoản cơ sở dữ liệu không được vượt quá 255 ký tự',
            'tenancy_db_password.required' => 'Vui lòng nhập mật khẩu',
            'tenancy_db_password.max' => 'Mật khẩu không được vượt quá 255 ký tự',
            'tenancy_db_password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'tenancy_db_password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt',
            'tenancy_db_password.not_regex' => 'Mật khẩu không dc chứa khoảng trắng',
            'tenancy_db_password_confirm.required_with' => 'Vui lòng nhập mật khẩu xác nhận',
            'tenancy_db_password_confirm.same' => 'Mật khẩu xác nhận không khớp',
            'tenancy_logo.mimes' => 'Tệp tải lên phải có định dạng jpeg, png, jpg, gif, svg',
            'tenancy_logo.max' => 'Tệp ảnh tải lên không được vượt quá 2MB',
            'tenancy_logo.image' => 'Tệp tải lên phải là ảnh.',
            'tenancy_logo' => 'Tệp tải lên không đúng định dạng hoặc vượt quá 2MB',
            'tenancy_group.exists' => 'Nhóm cửa hiệu không tồn tại',
            'tenancy_admin.exists' => 'Tài khoản quản trị không tồn tại',
            'tenancy_tiktok_url.max' => 'Liên kết Tiktok không được vượt quá 255 ký tự',
            'tenancy_fb_url.max' => 'Liên kết Facebook không được vượt quá 255 ký tự',
            'tenancy_ig_url.max' => 'Liên kết Instagram không được vượt quá 255 ký tự',
            'tenancy_address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'tenancy_domain.max' => 'Tên miền không được vượt quá 255 ký tự',
            'tenancy_domain.unique' => 'Tên miền đã tồn tại',
            'tenancy_domain.required' => 'Vui lòng nhập tên miền',
            'tenancy_domain.regex' => 'Tên miền chỉ được phép chứa chữ thường, số và dấu gạch nối, không được chứa khoảng trắng và ký tự đặc biệt.',
            'tenancy_is_active.required' => 'Vui chọn trạng thái cửa hiệu',
        ];
    }
    // ten mien phai chua .localhost moi chay dc, neu ko dat thì phai chinh cau hinh trong windows/system32/driver/etc/hosts de tro ve dia chi localhost
    protected function prepareForValidation()
    {
        $input = strtolower(trim($this->input('tenancy_domain')));
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        if ($input && !str_ends_with($input, $appHost)) {
            $subdomain = explode('.', $input)[0];
            $this->merge([
                'tenancy_domain' => "{$subdomain}.{$appHost}"
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $rules = $this->rules();

        $requiredFields = [];
        foreach ($rules as $field => $fieldRules) {
            $fieldRules = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($fieldRules as $rule) {
                if ($rule === 'nullable') {
                    continue 2;
                }

                if ($rule === 'required') {
                    $requiredFields[] = $field;
                    break;
                }
            }
        }

        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
                'required_fields' => $requiredFields
            ], 422)
        );
    }

    private function getPdoConfig($fail)
    {
        $host = $this->input('tenancy_db_host');
        $port = $this->input('tenancy_db_port');
        $connection = $this->input('tenancy_db_connection');

        if (!$host || !$port || !$connection) {
            return [null, null, null];
        }

        $adminConfig = config("database.connections.{$connection}");
        if (!$adminConfig) {
            $fail("Cấu hình kết nối {$connection} không tồn tại trong config/database.php");
            return [null, null, null];
        }

        return [$adminConfig, $host, $port];
    }

    private function validateDbName($attribute, $value, $fail)
    {
        $originalDbName = $this->route('tenant')->getOriginal('tenancy_db_name');
        if ($value === $originalDbName) {
            return;
        }
        list($adminConfig, $host, $port) = $this->getPdoConfig($fail);
        if (!$adminConfig) return;
        try {
            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new \PDO($dsn, $adminConfig['username'], $adminConfig['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 2,
            ]);
            $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
            $stmt->execute([$value]);

            if ($stmt->fetch()) {
                $fail("Tên cơ sở dữ liệu '{$value}' đã tồn tại trên server mysql.");
            }
        } catch (\PDOException $e) {
        }
    }


    private function validateDbUser($attribute, $value, $fail)
    {
        $originalUsername = $this->route('tenant')->getOriginal('tenancy_db_username');
        if ($value === $originalUsername) {
            return;
        }
        list($adminConfig, $host, $port) = $this->getPdoConfig($fail);
        if (!$adminConfig) return;
        try {
            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new \PDO($dsn, $adminConfig['username'], $adminConfig['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 2,
            ]);

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM mysql.user WHERE user = ?");
            $stmt->execute([$value]);

            if ($stmt->fetchColumn() > 0) {
                $fail("Tên tài khoản '{$value}' đã tồn tại trên server mysql.");
            }
        } catch (\PDOException $e) {
        }
    }


    private function validateDatabaseConnection($attribute, $value, $fail)
    {
        $host = $this->input('tenancy_db_host');
        $port = $this->input('tenancy_db_port');
        $connection = $this->input('tenancy_db_connection');
        $adminConfig = config("database.connections.{$connection}");
        if (!$adminConfig) {
            return $fail("Cấu hình kết nối {$connection} không tồn tại trong config/database.php");
        }
        try {
            $dsn = "mysql:host={$host};port={$port}";
            $pdo = new \PDO($dsn, $adminConfig['username'], $adminConfig['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 5,
            ]);
            $pdo->query("SELECT 1");
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            $lower = strtolower($msg);
            switch (true) {
                case str_contains($lower, 'getaddrinfo'):
                case str_contains($lower, 'no such host'):
                    $fail("Không thể tìm thấy host '{$host}'. Vui lòng kiểm tra lại tên địa chỉ host.");
                    break;
                case str_contains($lower, 'access denied for user'):
                    $fail("Sai kết nối không được phép cập nhật thông tin ở kết nối này.");
                    break;
                case str_contains($lower, 'connection refused'):
                    $fail("Kết nối bị từ chối.");
                    break;
                case str_contains($lower, 'no route to host'):
                    $fail("Không thể kết nối đến máy chủ mysql.");
                    break;
                case str_contains($lower, 'unknown database'):
                    $fail("Cơ sở dữ liệu không tồn tại hoặc bạn không có quyền truy cập.");
                    break;
                case str_contains($lower, '[2002]'):
                    $fail("Không thể kết nối đến mysql server");
                    break;
                default:
                    $fail("Lỗi kết nối mysql: " . $msg);
                    break;
            }
        }
    }
}
