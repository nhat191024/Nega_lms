import 'package:nega_lms/utils/imports.dart';

class LoginScreen extends GetView<LoginController> {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final screenHeight = MediaQuery.of(context).size.height;
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        toolbarHeight: screenHeight * 0.08,
        title: NavBar(),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.fromLTRB(100, 60, 200, 20),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Image.asset(
                    Images.login,
                    width: screenHeight * 0.8,
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        "Đăng nhập",
                        style: TextStyle(
                          fontSize: 24,
                          color: CustomColors.primary,
                          fontFamily: FontStyleTextStrings.medium,
                        ),
                      ),
                      CustomButton(
                        onTap: () {},
                        btnText: "Đăng nhập bằng Google",
                        width: Get.width * 0.2,
                        textColor: CustomColors.white,
                        btnColor: CustomColors.primary,
                        prefixSvgImage: Images.googleIcon,
                        prefixTextGap: 40,
                        borderRadius: 8,
                        leftPadding: 0,
                        rightPadding: 0,
                        topPadding: 40,
                        bottomPadding: 30,
                      ),
                      SizedBox(
                        width: Get.width * 0.2,
                        child: const Row(
                          children: [
                            Expanded(
                              child: Divider(
                                color: CustomColors.dividers,
                                thickness: 1,
                                height: 20,
                              ),
                            ),
                            SizedBox(width: 10),
                            Text(
                              "Hoặc tiếp tục với",
                              style: TextStyle(
                                color: CustomColors.primaryText,
                                fontSize: 12,
                                fontFamily: FontStyleTextStrings.medium,
                              ),
                            ),
                            SizedBox(width: 10),
                            Expanded(
                              child: Divider(
                                color: CustomColors.dividers,
                                thickness: 1,
                                height: 20,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Obx(
                        () => CustomTextField(
                          labelText: "Tài khoản đăng nhập",
                          hintText: "Nhập tài khoản hoặc email",
                          keyboardType: TextInputType.emailAddress,
                          controller: controller.username,
                          onChanged: (value) {
                            if (value.isEmpty) {
                              controller.usernameErrorText.value = "Vui lòng nhập tài khoản hoặc email";
                              controller.isUsernameError.value = true;
                            } else {
                              controller.usernameErrorText.value = "";
                              controller.isUsernameError.value = false;
                              // if (!GetUtils.isEmail(value)) {
                              //   controller.usernameErrorText.value = "Email không hợp lệ";
                              //   controller.isUsernameError.value = true;
                              // } else {
                              //   controller.usernameErrorText.value = "";
                              //   controller.isUsernameError.value = false;
                              // }
                            }
                          },
                          errorText: controller.usernameErrorText.value,
                          isError: controller.isUsernameError,
                          obscureText: false.obs,
                          width: Get.width * 0.2,
                          border: 8,
                          leftPadding: 0,
                          rightPadding: 0,
                          topPadding: 30,
                          bottomPadding: 20,
                        ),
                      ),
                      Padding(
                        padding: const EdgeInsets.fromLTRB(0, 0, 0, 20),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              "Mật khẩu",
                              style: TextStyle(
                                fontSize: 14,
                                fontFamily: FontStyleTextStrings.bold,
                                color: CustomColors.secondaryText,
                              ),
                            ),
                            const SizedBox(height: 5),
                            SizedBox(
                              width: Get.width * 0.2,
                              child: Obx(
                                () => TextField(
                                  obscureText: controller.isObscureText.value,
                                  keyboardType: TextInputType.visiblePassword,
                                  controller: controller.password,
                                  onChanged: (value) {
                                    if (value.isEmpty) {
                                      controller.passwordErrorText.value = "Vui lòng nhập mật khẩu";
                                      controller.isPasswordError.value = true;
                                    } else {
                                      if (value.length < 6) {
                                        controller.passwordErrorText.value = "Mật khẩu phải có ít nhất 6 ký tự";
                                        controller.isPasswordError.value = true;
                                      } else {
                                        controller.passwordErrorText.value = "";
                                        controller.isPasswordError.value = false;
                                      }
                                    }
                                  },
                                  decoration: InputDecoration(
                                    hintText: "Nhập mật khẩu của bạn",
                                    hintStyle: const TextStyle(color: CustomColors.disable),
                                    contentPadding: const EdgeInsets.fromLTRB(15, 15, 10, 15),
                                    border: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(8),
                                      borderSide: BorderSide(
                                        color: controller.isPasswordError.value ? CustomColors.errorMain : CustomColors.dividers,
                                        width: 1,
                                      ),
                                    ),
                                    enabledBorder: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(14),
                                      borderSide: BorderSide(
                                        color: controller.isPasswordError.value ? CustomColors.errorMain : CustomColors.dividers,
                                        width: 1,
                                      ),
                                    ),
                                    errorText: controller.isPasswordError.value ? controller.passwordErrorText.value : null,
                                    focusedBorder: OutlineInputBorder(
                                      borderSide: BorderSide(
                                          color: controller.isPasswordError.value ? CustomColors.errorMain : CustomColors.primaryText, width: 1),
                                      borderRadius: BorderRadius.circular(14),
                                    ),
                                    filled: controller.isPasswordError.value,
                                    fillColor: controller.isPasswordError.value ? CustomColors.errorLight : CustomColors.background,
                                    suffixIcon: iconButton(
                                      icon: controller.isObscureText.value ? Icons.visibility_off : Icons.visibility,
                                      onPressed: () {
                                        controller.isObscureText.value = !controller.isObscureText.value;
                                      },
                                    ),
                                    alignLabelWithHint: true,
                                    isDense: true,
                                    suffixIconConstraints: const BoxConstraints(
                                      minWidth: 0,
                                      minHeight: 0,
                                    ),
                                    floatingLabelBehavior: FloatingLabelBehavior.always,
                                  ),
                                ),
                              ),
                            )
                          ],
                        ),
                      ),
                      Obx(
                        () => CustomButton(
                          onTap: () {
                            controller.login();
                          },
                          isLoading: controller.isButtonLoading.value,
                          btnText: "Đăng nhập",
                          width: Get.width * 0.2,
                          textColor: CustomColors.white,
                          btnColor: CustomColors.primary,
                          borderRadius: 8,
                          leftPadding: 0,
                          rightPadding: 0,
                          bottomPadding: 40,
                        ),
                      ),
                    ],
                  )
                ],
              ),
            ),
            const Footer(),
          ],
        ),
      ),
    );
  }

  Widget iconButton({required IconData icon, required VoidCallback onPressed}) {
    return IconButton(
      icon: Icon(icon),
      onPressed: onPressed,
    );
  }
}
