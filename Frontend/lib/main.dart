import 'package:nega_lms/utils/imports.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  // setUrlStrategy(PathUrlStrategy());
  await GetStorage.init();
  runApp(const MyApp());
}
