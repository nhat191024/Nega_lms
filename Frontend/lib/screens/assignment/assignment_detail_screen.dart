import 'package:nega_lms/utils/imports.dart';

class AssignmentDetailScreen extends GetView<AssignmentController> {
  const AssignmentDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Assignment Detail'),
      ),
      body: const Center(
        child: Text('Assignment Detail'),
      ),
    );
  }
}
