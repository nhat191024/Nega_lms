import 'package:nega_lms/utils/imports.dart';

class LoginController extends GetxController {
  final TextEditingController username = TextEditingController();
  final RxBool isUsernameError = false.obs;
  final RxString usernameErrorText = "".obs;
  final TextEditingController password = TextEditingController();
  final RxBool isPasswordError = false.obs;
  final RxBool isObscureText = true.obs;
  final RxString passwordErrorText = "".obs;
}