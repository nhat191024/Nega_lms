import 'package:nega_lms/utils/imports.dart';

class DoAssignmentScreen extends GetView<AssignmentController> {
  const DoAssignmentScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Do Assignment'),
      ),
      body: const Center(
        child: Text('Do Assignment'),
      ),
    );
  }
}
