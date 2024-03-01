<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @author lihongyan(lihongyan@babeltime.com)
 * @date 2024/2/22
 * @version ${Revision}
 * @brief 验证请求
 *
 */
class SeckillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 只有经过身份验证的用户才能参与秒杀。还可以定义别的用户类型啥的
        return auth()->check(); // 如果用户已认证，返回 true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id', // 确保商品ID存在
            'quantity' => 'required|integer|min:1', // 确保购买数量是整数且大于0
            // 其他你需要的验证规则
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_id.required' => '商品ID不能为空',
            'product_id.exists' => '商品不存在',
            'quantity.required' => '购买数量不能为空',
            'quantity.integer' => '购买数量必须是整数',
            'quantity.min' => '购买数量不能小于1',
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '验证码不正确',
        ];
    }
}
