part of 'pages.dart';

abstract class Routes {
  Routes._();

  static const homePage = _Paths.homePage;
  static const loginPage = _Paths.loginPage;
  static const classListPage = _Paths.classListPage;
  static const assignmentListPage = _Paths.assignmentListPage;
  static const doAssignmentScreen = _Paths.doAssignmentScreen;
}

abstract class _Paths {
  static const homePage = '/';
  static const loginPage = '/login';
  static const classListPage = '/class-list';
  static const assignmentListPage = '/assignment-list';
  static const doAssignmentScreen = '/do-assignment';
}