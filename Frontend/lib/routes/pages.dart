import 'package:nega_lms/utils/imports.dart';

part 'routes.dart';

class Pages {
  Pages._();

  static const initialRoute = Routes.homePage;

  static final routes = [
    GetPage(
      name: _Paths.homePage,
      page: () => const HomeScreen(),
      binding: HomeBinding(),
    ),
    GetPage(
      name: _Paths.loginPage,
      page: () => const LoginScreen(),
      binding: LoginBinding(),
    ),
    GetPage(
      name: _Paths.classListPage,
      page: () => ClassListScreen(),
      binding: ClassBinding(),
    ),
    GetPage(
      name: _Paths.classDetailScreen,
      page: () => const ClassDetailScreen(),
      binding: AssignmentBinding(),
    ),
    GetPage(
      name: _Paths.doAssignmentScreen,
      page: () => const DoAssignmentScreen(),
      binding: AssignmentBinding(),
    ),
  ];
}
