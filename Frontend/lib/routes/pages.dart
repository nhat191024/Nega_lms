import 'package:nega_lms/utils/imports.dart';

part 'routes.dart';

class Pages {
  Pages._();

  static const initialRoute = Routes.loginPage;

  static final routes = [
    GetPage(
      name: _Paths.homePage,
      page: () => const LayoutScreen(),
      binding: LayoutBinding(),
    ),
    GetPage(
      name: _Paths.loginPage,
      page: () => const LoginScreen(),
      binding: LoginBinding(),
    ),
    GetPage(
      name: _Paths.doAssignmentScreen,
      page: () => const DoAssignmentScreen(),
      binding: AssignmentBinding(),
    ),
  ];
}
